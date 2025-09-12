<?php

namespace App\Http\Controllers;

use App\Models\Waypoint;
use Illuminate\Http\Request;

class WaypointController extends Controller
{
    // Display map with user's waypoints
    public function index()
    {
        $waypoints = Waypoint::where('user_id', auth()->id())->get();
        return view('maps.index', compact('waypoints'));
    }

    // Store a new waypoint
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $data['user_id'] = auth()->id();
        Waypoint::create($data);

        return redirect()->route('maps.index')->with('success', 'Waypoint added!');
    }
}
