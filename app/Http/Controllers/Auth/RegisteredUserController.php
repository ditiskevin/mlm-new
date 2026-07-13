<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register', [
            'parentTypes' => User::parentTypeMap(),
            'genders' => User::GENDERS,
            'parentingRoles' => User::PARENTING_ROLES,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'parent_type' => ['required', 'in:'.implode(',', array_keys(User::parentTypeMap()))],
            'gender' => ['nullable', 'in:'.implode(',', array_keys(User::GENDERS))],
            'parenting_role' => ['nullable', 'in:'.implode(',', array_keys(User::PARENTING_ROLES))],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'parent_type' => $request->parent_type,
            'gender' => $request->gender,
            'parenting_role' => $request->parenting_role,
        ]);

        \App\Support\BadgeService::award($user, 'welkom');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('onboarding.show', absolute: false));
    }
}
