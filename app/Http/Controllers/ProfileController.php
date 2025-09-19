<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Show profile edit page
    public function edit(Request $request)
{
    $user = $request->user();

    // Get all polygons for the logged-in user
    $polygons = $user->polygons()->get();

    return view('profile.edit', compact('user', 'polygons'));
}


    // Update profile
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated!');
    }

    // Delete profile
    public function destroy()
    {
        $user = Auth::user();
        $user->delete();

        return redirect('/')->with('success', 'Profile deleted.');
    }
}
