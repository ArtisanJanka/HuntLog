<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Waypoint;
use App\Models\Polygon;
use Illuminate\Support\Facades\Auth;

class WaypointController extends Controller
{
    // Show full map with all waypoints and polygons
    public function showMap()
    {
        $user = auth()->user(); // current logged-in user
        $waypoints = $user->waypoints()->get(); // fetch all waypoints
        $polygons = $user->polygons()->get();   // fetch all polygons

        return view('maps.index', compact('waypoints', 'polygons'));
    }

    // Show single waypoint (optional)
    public function show(Waypoint $waypoint)
    {
        $user = Auth::user();

        // Only return the waypoint if it belongs to the user
        if ($waypoint->user_id !== $user->id) {
            abort(403);
        }

        return view('maps.show', [
            'waypoints' => [$waypoint], // single waypoint in array
            'polygons' => []
        ]);
    }

    // Show single polygon
    public function showPolygon(Polygon $polygon)
    {
        $user = Auth::user();

        if ($polygon->user_id !== $user->id) {
            abort(403);
        }

        // Ensure coordinates are decoded if stored as JSON
        $coordinates = is_string($polygon->coordinates) ? json_decode($polygon->coordinates, true) : $polygon->coordinates;

        return view('maps.show', [
            'waypoints' => [],
            'polygons' => [
                [
                    'name' => $polygon->name,
                    'coordinates' => $coordinates
                ]
            ]
        ]);
    }
    public function storePolygon(Request $request)
    {
    $request->validate([
        'name' => 'required|string|max:255',
        'coordinates' => 'required|string',
    ]);

    Polygon::create([
        'name' => $request->name,
        'coordinates' => $request->coordinates,
        'user_id' => auth()->id(),
    ]);

    return redirect()->back()->with('success', 'Polygon saved!');
    }
}
