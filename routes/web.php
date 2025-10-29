<?php

use App\Http\Controllers\ProfileController;
use App\Models\MemberInvite;
use App\Models\Membership;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('OS');
});

Route::post('/membership/new', function (Request $request) {
    $code = MemberInvite::query()->where('code', '=', $request->string('code'))->first();

    if (!$code) return back()->withErrors(['code' => 'Invalid Code']);

    if ($code->consumers()->count() >= $code->uses) return back()->withErrors(['code' => 'This code already in use']);

    $ms = new Membership();
    $ms->tier = 1;
    $ms->club_id = $code->club_id;
    $ms->user_id = $request->user()->id;
    $ms->code_id = $code->id;
    $ms->save();

    return back();
})->middleware(['auth']);

Route::get('/clubs', function (Request $request) {
    return $request->user()->clubs;
})->middleware(['auth']);

Route::get('/club/{club:id}', function (\Illuminate\Http\Request $request, \App\Models\Club $club) {
    $club->load(['posts' => function($q) use ($request, $club) {
        if (auth()->check() && in_array($request->user()->id, [1, 3, $club->owner_id])) {
            $q->orderByRaw('CASE WHEN `posts`.`published_at` IS NULL THEN 1 ELSE 0 END DESC');
        } else {
            $q->where('published_at', '<', now());
        }

        $q->latest('published_at')->with(['comments.author']);
    }]);

    $club->posts->map(function ($p) {
        $paths = \Illuminate\Support\Facades\Storage::disk('public')->files('posts/' . $p->id);
        $files = [];

        foreach ($paths as $file) $files[] = [
            'path' => $file,
            'size' => \Illuminate\Support\Facades\Storage::disk('public')->size($file),
//            'mime' => \Illuminate\Support\Facades\Storage::disk('public')->mimeType($file),
            'name' => pathinfo($file, PATHINFO_FILENAME) . '.' . pathinfo($file, PATHINFO_EXTENSION),
        ];

        $p->setAttribute('files', $files);
    });

    return $club;
});

Route::patch('/posts/{post:id}/edit', function (\Illuminate\Http\Request $request, \App\Models\Post $post) {
    // Must have edit perms
    if ($request->user()->id !== $post->club->owner_id && !in_array($request->user()->id, [1, 3])) abort(403);

    $request->validate([
        'title' => ['nullable'],
        'blurb' => ['nullable'],
        'content' => ['nullable'],
        'published_at' => ['nullable'],
    ]);

    $post->title = (string)$request->string('title', $post->title);
    $post->blurb = (string)$request->string('blurb', $post->blurb);
    $post->published_at = $request->get('published_at') ? $request->date('published_at') : null;
    $post->content = (string)$request->string('content', $post->content);
    $post->save();

    return back();
})->middleware(['auth']);

Route::get('/chat', function (\Illuminate\Http\Request $request) {
    return \App\Models\ChatMessage::query()->latest()->with(['author'])->limit(10)->get();
});

Route::get('/events', function (\Illuminate\Http\Request $request) {
    return \App\Models\Events::query()->latest()->limit(10)->get();
});

Route::post('/club/{club:id}/posts/new', function (\Illuminate\Http\Request $request, \App\Models\Club $club) {
    // TODO: post authorization

   if ($request->user()->id !== $club->owner_id && !in_array($request->user()->id, [1, 3])) abort(403);

    $request->validate([
        'title' => 'required',
        'content' => 'required',
    ]);

    $post = new \App\Models\Post();
    $post->title = $request->string('title');
    $post->slug = Str::slug($request->string('title'));
    $post->content = $request->string('content');
    $post->blurb = $request->string('blurb');
    $post->author_id = $request->user()->id;
    $post->club_id = $club->id;
    $post->published_at = $request->string('published_at', now());
    $post->save();

    $files = $request->file('files');
    if($request->hasFile('files')) {
        foreach ($files as $file) {
            $name = $file->getClientOriginalName();
            $ext = $file->guessClientExtension();
            $id = $post->id;
            $file->storeAs("posts/$id", "$name", 'public');
        }
    }

    // unrelated but $request->fingerprint(); what is this?

    return back();
})->middleware(['auth']);

Route::post('/feedback/new', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'name' => 'required',
        'type' => 'required',
        'description' => 'required',
    ]);

    $feedback = new \App\Models\Feedback();

    if (auth()->check()) {
        $feedback->user_id = $request->user()->id;
        $feedback->name = $request->user()->name;
    } else {
        $feedback->name = $request->string('name');
    }

    $feedback->type = $request->string('type');
    $feedback->subject = $request->string('subject');
    $feedback->description = $request->string('description');
    $feedback->data = json_encode([
        'agent' => $request->string('userAgent')
    ]);
    $feedback->save();

    return back();
});

Route::post('/chat/send', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'message' => ['required', 'max:240']
    ]);

    $msg = new \App\Models\ChatMessage();
    $msg->user_id = $request->user()->id;
    $msg->message = $request->string('message');
    $msg->save();

    \App\Events\ChatMessageCreated::dispatch(
        $msg,
        $request->user()
    );
})->middleware(['auth']);

//Route::get('/posts', function () {
//    $posts = \App\Models\Post::all()->load(['author']);
//
//    return Inertia::render('_Posts', [
//        'posts' => $posts,
//        'club' => \App\Models\Club::query()->find(1)
//    ]);
//});

Route::get('/join', function () {
    return Inertia::render('Join');
});

Route::get('/mock', function () {
    return Inertia::render('Mock');
});

//Route::get('/demos:new-club', function () {
//    return Inertia::render('Demo/NewClub');
//});
//Route::post('/demos:new-club', function (\Illuminate\Http\Request $request) {
//    $request->validate([
//        'name' => 'required',
//        'description' => 'required',
//        'owner_id' => 'required',
//    ]);
//
//    $club = new \App\Models\Club();
//    $club->name = $request->string('name');
//    $club->description = $request->string('description');
//    $club->owner_id = $request->integer('owner_id');
//    $club->save();
//
//    // unrelated but $request->fingerprint(); what is this?
//
//    return $club;
//});

//Route::get('/demos:new-post', function () {
//    return Inertia::render('Demo/NewPost');
//});
//


Route::prefix('/admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', function (Request $request) {
        return Inertia::render('Admin/Home', [
            'stats' => [
                'users' => \App\Models\User::query()->count(),
                'memberships' => \App\Models\Membership::query()->count(),
                'codes' => \App\Models\MemberInvite::query()->count(),
                'posts' => \App\Models\Post::query()->count(),
                'chats' => \App\Models\ChatMessage::query()->count(),
            ]
        ]);
    });

    Route::get('/clubs', function (Request $request) {
        return Inertia::render('Admin/Clubs', [
            'clubs' => \App\Models\Club::query()
                ->latest()
                ->with(['owner'])
                ->withCount(['posts'])
                ->get(),
        ]);
    });

    Route::post('/clubs/new', function (Request $request) {
        $club = new \App\Models\Club();
        $club->name = $request->string('name');
        $club->description = $request->string('description');
        $club->owner_id = $request->integer('owner_id');
        $club->save();

        return back();
    });

    Route::get('/feedback', function (Request $request) {
        return Inertia::render('Admin/Feedback', [
            'feedback' => \App\Models\Feedback::query()->latest()->get(),
        ]);
    });

    Route::get('/events', function (Request $request) {
        return Inertia::render('Admin/Events', [
            'events' => \App\Models\Events::query()->latest()->get(),
        ]);
    });

    Route::post('/events/new', function (Request $request) {
        $event = new \App\Models\Events();
        $event->name = $request->string('name');
        $event->description = $request->string('description');
        $event->location = $request->string('location');
        if ($request->string('date')->toString() !== '') $event->date = $request->string('date');
        $event->purchase_url = $request->string('purchase_url');
        $event->info_url = $request->string('info_url');
        $event->save();

        return back();
    });

    Route::get('/codes', function (Request $request) {
        if ($request->user()->id !== 1) abort(400);

        return Inertia::render('Admin/Codes', [
            'codes' => \App\Models\MemberInvite::query()->latest('id')->paginate(50)
        ]);
    });

    Route::post('/codes', function (Request $request) {
        $request->validate([
            'prefix' => ['required', 'string'],
            'count' => ['required', 'integer'],
            'club_id' => ['required', 'integer'],
        ]);

        $needed = $request->integer('count');

        $checked = [];
        $init = true;
        do {
            $codes = [];

            // TODO@later: if given list with already sent codes, tell the user instead of regenerating more

            // We have a given list
            if ($request->has('codes') && $request->input('codes') !== null && $init) {
                // Fill the first batch with provided codes
                $codes = $request->json('codes');
                // The amount needed should match the provided codes, to reduce confusion
                if (count($codes) !== $needed) abort(400);
                // Don't generate an initial iteration when we get provided codes
                $needed = 0;
            }

            // We've run (skipped) the first generation, don't try to load on provided codes on subsequent runs
            $init = false;

            // Generate codes until we need 0 more
            for (; $needed > 0; $needed--) {
                $a = rand(pow(10, 3), pow(10, 4) - 1);
                $b = rand(pow(10, 3), pow(10, 4) - 1);
                $c = rand(pow(10, 3), pow(10, 4) - 1);

                $code = $request->string('prefix')->upper() . "-{$a}-{$b}-{$c}";

                if (in_array($code, $codes) || in_array($code, $checked)) {
                    // Code already generated in this or another run, rewind and do this iteration again
                    $needed++;
                } else {
                    $codes[] = $code;
                }
            }

            // Get list of codes that are already in the DB
            $duplicates = \App\Models\MemberInvite::query()
                ->whereIn('code', $codes)
                ->get('code')
                ->toArray();

            for ($i = 0; $i < count($codes); $i++) {
                if (in_array($codes[$i], $duplicates)) {
                    // If current code is already in the DB, we need a new one
                    $needed++;
                } else {
                    // Otherwise this code is approved for creation
                    $checked[] = $codes[$i];
                }
            }
        } while ($needed > 0);

        $records = [];

        for ($i = 0; $i < count($checked); $i++) {
            $records[] = [
                'creator_id' => $request->user()->id,
                'club_id' => $request->integer('club_id'),
                'code' => $checked[$i],
                'uses' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        $query = \App\Models\MemberInvite::query()->insert($records);

        return Inertia::render('Admin/Codes', [
            'codes' => \App\Models\MemberInvite::query()->latest('id')->paginate(50),
            'result' => [
                'succeeded' => $query,
                'timestamp' => now(),
                'prefix' => $request->string('prefix'),
                'creator_id' => $request->user()->id,
                'club_id' => $request->integer('club_id'),
                'codes' => $checked
            ]
        ]);
    })->middleware(['auth']);

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
