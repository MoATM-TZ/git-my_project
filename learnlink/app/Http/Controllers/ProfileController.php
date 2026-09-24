<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /** Profile overview + edit form (legacy profile.php). */
    public function edit(Request $request): View
    {
        $user = $request->user()->loadCount(['resources', 'comments']);

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /** Persist profile changes (legacy profile_update.php). */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            // Optional password change; requires the current password.
            'current_password' => ['required_with:new_password', 'nullable', 'current_password:web'],
            'new_password'     => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $user->fill([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['new_password'])) {
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return back()->with('status', 'Profile updated successfully.');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $user = $request->user();

        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $user->avatar_path = $validated['avatar']->store('avatars', 'public');
        $user->save();

        return back()->with('status', 'Profile picture updated.');
    }

    public function deleteAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
            $user->avatar_path = null;
            $user->save();
        }

        return back()->with('status', 'Profile picture removed.');
    }
}
