<?php

namespace App\Http\Controllers;

use App\Models\Polygon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PolygonController extends Controller
{
    // Show all polygons for the authenticated user
    public function index()
    {
        $polygons = Polygon::where('user_id', Auth::id())->get();
        return view('polygons.index', compact('polygons'));
    }

    // Store a new polygon
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'coordinates' => 'required|string', // JSON string from frontend
        ]);

        Polygon::create([
            'name' => $request->name,
            'coordinates' => $request->coordinates,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Polygon saved!');
    }

    // Show a single polygon on the map
    public function show(Polygon $polygon)
    {
        $this->authorize('view', $polygon); // Optional: policy check

        return view('maps.polygon', compact('polygon'));
    }
    public function userPolygons()
    {

        $polygons = Polygon::where('user_id', Auth::id())->get(); // only their polygons
        return view('maps.index', compact('polygons'));
    }

    public function edit(Request $request)
    {
        $user = $request->user();
        $polygons = $user->polygons()->get(); // assuming Polygon has user_id
        return view('profile.edit', compact('user', 'polygons'));
    }

}
