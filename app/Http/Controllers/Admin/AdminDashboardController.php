<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\GalleryItem;
use App\Models\HuntingType;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $galleryCount = GalleryItem::count();
        $huntingTypeCount = HuntingType::count();
        $users = User::all();

        return view('admin.dashboard', compact('galleryCount', 'huntingTypeCount', 'users'));
    }

    public function makeLeader(User $user)
    {
        $user->is_leader = true;
        $user->save();

        return back()->with('success', "{$user->name} is now a leader.");
    }

    public function removeLeader(User $user)
    {
        $user->is_leader = false;
        $user->save();

        return back()->with('success', "{$user->name} is no longer a leader.");
    }
}
