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
            'coordinates' => 'required|json',
        ]);

        Polygon::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'coordinates' => $request->coordinates,
        ]);

        return back()->with('success', 'Polygon saved successfully!');
    }

    // Show a single polygon on the map
    public function show(Polygon $polygon)
    {
        $this->authorize('view', $polygon); // ensure only owner sees it
        return view('map.show', compact('polygon'));
    }
}
