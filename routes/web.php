<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/club/{club:id}', function (\Illuminate\Http\Request $request, \App\Models\Club $club) {
    $club->load(['posts' => function($q) use ($request, $club) {
        $q->latest('published_at');

        if (auth()->check() && !in_array($request->user()->id, [1, $club->owner_id])) {
            $q->where('published_at', '<', now());
        }
    }]);

    $club->posts->map(function ($p) {
        $p->files = \Illuminate\Support\Facades\Storage::disk('public')->files('posts/' . $p->id);
    });

    return $club;
});

Route::get('/chat', function (\Illuminate\Http\Request $request) {
    return \App\Models\ChatMessage::query()->latest()->with(['author'])->limit(10)->get();
});

Route::post('/club/{club:id}/posts/new', function (\Illuminate\Http\Request $request, \App\Models\Club $club) {
    // TODO: post authorization

   if ($request->user()->id !== $club->owner_id) abort(403);

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

Route::post('/early-access', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'code' => ['required', 'string']
    ]);

    $code = \App\Models\MemberInvite::query()->where('code', '=', $request->string('code'))->first();

    if (!$code) return back()->withErrors(['code' => 'Invalid Code']);

    return redirect(\Illuminate\Support\Facades\URL::temporarySignedRoute(
        'register',
        now()->addMinutes(60),
        [
            'code' => $code->code
        ]
    ));
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

Route::get('/generate-codes', function (Request $request) {
    if ($request->user()->id !== 1) abort(400);

    $request->validate([
        'prefix' => ['required', 'string'],
        'count' => ['required', 'integer'],
        'club_id' => ['required', 'integer'],
    ]);

    $needed = $request->integer('count');
    $checked = [];

    do {
        $codes = [];

        for (; $needed > 0; $needed--) {
            $a = rand(pow(10, 3), pow(10, 4) - 1);
            $b = rand(pow(10, 3), pow(10, 4) - 1);
            $c = rand(pow(10, 3), pow(10, 4) - 1);

            $code = $request->string('prefix')->upper() . "-{$a}-{$b}-{$c}";

            if (in_array($code, $codes) || in_array($code, $checked)) {
                // Code already generated this batch, rewind and do this iteration again
                $needed++;
            } else {
                $codes[] = $code;
            }
        }

        $duplicates = \App\Models\MemberInvite::query()
            ->whereIn('code', $codes)
            ->get('code')
            ->toArray();

        for ($i = 0; $i < count($codes); $i++) {
            if (in_array($codes[$i], $duplicates)) {
                // If current code is already in the DB,
                $needed++;
            } else {
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
            'uses' => 1
        ];
    }

    return [
        'succeeded' => \App\Models\MemberInvite::query()->insert($records),
        'creator_id' => $request->user()->id,
        'club_id' => $request->integer('club_id'),
        'codes' => $checked
    ];
})->middleware(['auth']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
