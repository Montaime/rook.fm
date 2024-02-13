<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
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
    return auth()->check() ? Inertia::render('OS') : Inertia::render('Home');
});

Route::post('/early-access', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'code' => ['required', 'string']
    ]);

    $code = \App\Models\MemberInvite::query()->where('code', '=', $request->string('code'))->first();

    if (!$code) return back()->withErrors(['code' => 'Invalid Code']);

    return redirect(\Illuminate\Support\Facades\URL::temporarySignedRoute('register', now()->addMinutes(60), ['code' => $request->string('code')]));
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
        $request->string('message'),
        $request->user()
    );
})->middleware(['auth']);

Route::get('/posts', function () {
    $posts = \App\Models\Post::all()->load(['author']);

    return Inertia::render('_Posts', [
        'posts' => $posts,
        'club' => \App\Models\Club::query()->find(1)
    ]);
});

Route::get('/join', function () {
    return Inertia::render('Join');
});

Route::get('/mock', function () {
    return Inertia::render('Mock');
});

Route::get('/demos:new-club', function () {
    return Inertia::render('Demo/NewClub');
});
Route::post('/demos:new-club', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'name' => 'required',
        'description' => 'required',
        'owner_id' => 'required',
    ]);

    $club = new \App\Models\Club();
    $club->name = $request->string('name');
    $club->description = $request->string('description');
    $club->owner_id = $request->integer('owner_id');
    $club->save();

    // unrelated but $request->fingerprint(); what is this?

    return $club;
});

Route::get('/demos:new-post', function () {
    return Inertia::render('Demo/NewPost');
});

Route::post('/demos:new-post', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'name' => 'required',
        'description' => 'required',
        'owner_id' => 'required',
    ]);

    $club = new \App\Models\Post();
    $club->title = $request->string('name');
    $club->slug = $request->string('slug');
    $club->content = $request->string('content');
    $club->author_id = $request->integer('author_id');
    $club->club_id = $request->integer('club_id');
    $club->published_at = $request->integer('published_at');
    $club->save();

    // unrelated but $request->fingerprint(); what is this?

    return $club;
});

Route::get('/generate', function () {

    function g(): string
    {
        $a = rand(pow(10, 3), pow(10, 4) - 1);
        $b = rand(pow(10, 3), pow(10, 4) - 1);
        $c = rand(pow(10, 3), pow(10, 4) - 1);
        return "MERE-${a}-${b}-${c}";
    }

    $codes = [];
    for ($i = 0; $i < 200; $i++) {
        $codes[] = g();
    }

    return $codes;
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
