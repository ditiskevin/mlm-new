<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Support\AvatarProcessor;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'parentTypes' => User::parentTypeMap(),
            'genders' => User::GENDERS,
            'parentingRoles' => User::PARENTING_ROLES,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->safe()->except(['avatar', 'remove_avatar']));

        if ($request->hasFile('avatar')) {
            $old = $user->avatar_path;
            // Normalise to a square WebP before storing to keep avatars small & consistent.
            $path = 'avatars/'.Str::uuid()->toString().'.webp';
            Storage::disk('r2')->put($path, AvatarProcessor::toWebp($request->file('avatar')), 'public');
            $user->avatar_path = $path;
            if ($old) {
                Storage::disk('r2')->delete($old);
            }
        } elseif ($request->boolean('remove_avatar') && $user->avatar_path) {
            // Revert to the colour avatar.
            Storage::disk('r2')->delete($user->avatar_path);
            $user->avatar_path = null;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
