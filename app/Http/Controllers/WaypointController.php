<?php

namespace App\Http\Controllers;

use App\Models\Waypoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaypointController extends Controller
{
    // Show profile page with user waypoints
    public function profile()
    {
        $user = Auth::user(); 
    $waypoints = Waypoint::where('user_id', $user->id)->get();

    return view('profile.edit', compact('user', 'waypoints'));
    }

    // Show map page (all waypoints)
    public function map()
    {
        $waypoints = Waypoint::where('user_id', Auth::id())->get();
        return view('maps.index', compact('waypoints'));
    }

    // Show single waypoint on map
    public function show(Waypoint $waypoint)
    {
        $this->authorize('view', $waypoint); // ensure only owner can view
        return view('map.show', compact('waypoint'));
    }

    // Store new waypoint
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        Waypoint::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Waypoint saved successfully!');
    }
}
