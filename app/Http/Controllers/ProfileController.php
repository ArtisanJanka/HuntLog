<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        $polygons = $user->polygons()->get();

        return view('profile.edit', compact('user', 'polygons'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            // Breeze expects the lowercase + unique rule with the user's id ignored
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        // Only reset verification if the email actually changed
        if ($validated['email'] !== $user->email) {
            $user->email_verified_at = null;
        }

        $user->fill($validated)->save();

        // IMPORTANT: redirect to /profile, not back() or /
        return Redirect::route('profile.edit');
    }

    public function destroy(Request $request)
    {
        // Validate into the *userDeletion* error bag and use current_password
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Log out and clear session before deleting
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Breeze tests expect redirect to '/'
        return Redirect::to('/');
    }
}
