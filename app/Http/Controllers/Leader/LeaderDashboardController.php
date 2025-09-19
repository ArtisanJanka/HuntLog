<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\GroupRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LeaderDashboardController extends Controller
{
    /**
     * Display the leader dashboard with assigned users and pending requests.
     */
    public function index()
    {
        $leader = Auth::user();

        // Users already assigned to this leader and approved
        $users = User::where('leader_id', $leader->id)
                     ->where('status', 'approved')
                     ->get();

        // Pending join requests for this leader
        $requests = GroupRequest::with('user', 'huntingType')
                        ->where('status', 'pending')
                        ->get();

        return view('leader.dashboard', compact('users', 'requests'));
    }

    /**
     * Create a new user under this leader.
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'leader_id' => Auth::id(),
            'status'    => 'approved', // automatically approved
        ]);

        return redirect()->route('leader.dashboard')->with('success', 'User created successfully.');
    }

    /**
     * Approve a pending group request.
     */
    public function acceptRequest(GroupRequest $request)
    {
        // Optionally, you could assign the user to this leader here
        $request->update(['status' => 'approved']);
        
        return back()->with('success', 'Request approved.');
    }

    /**
     * Reject a pending group request.
     */
    public function rejectRequest(GroupRequest $request)
    {
        $request->delete();
        return back()->with('success', 'Request rejected.');
    }
}
