<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MemberInvite;
use App\Models\Membership;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/Register', [
            'code' => $request->string('code')
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:24', 'regex:/[a-zA-Z0-9]/', 'unique:'.User::class],
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $code = null;
        if ($request->filled('code')) {
            $code = MemberInvite::query()->where('code', '=', $request->string('code'))->first();

            if (!$code) {
                return back()->withErrors(['code' => 'Invalid Code']);
            }

            if ($code->consumers()->count() >= $code->uses) {
                return back()->withErrors(['code' => 'This code already in use']);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->filled('code')) {
            $ms = new Membership();
            $ms->tier = 1;
            $ms->club_id = $code->club_id;
            $ms->user_id = $user->id;
            $ms->code_id = $code->id;
            $ms->save();
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
