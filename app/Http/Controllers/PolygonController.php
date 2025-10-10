<?php

namespace App\Http\Controllers;

use App\Models\Polygon;
use Illuminate\Http\Request;

class PolygonController extends Controller
{
    /**
     * List polygons the user is allowed to see (owner OR same group).
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Polygon::class);

        $polygons = Polygon::visibleTo($request->user())->get();

        return view('polygons.index', compact('polygons'));
    }

    /**
     * Create a polygon in one of the user's groups.
     * Expects: name (string), coordinates (JSON string), group_id (int).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'coordinates' => 'required|string',   // JSON string from the client
            'group_id'    => 'required|exists:groups,id',
        ]);

        // Ensure the user actually belongs to the chosen group
        abort_unless(
            $request->user()->groups()->whereKey($data['group_id'])->exists(),
            403
        );

        // Decode coordinates (handle double-encoded JSON)
        $coords = json_decode($data['coordinates'], true);
        if (is_string($coords)) {
            $coords = json_decode($coords, true);
        }
        if (!is_array($coords) || count($coords) < 3) {
            return back()->withErrors([
                'coordinates' => 'Nepareizas poligona koordinātas (vajag vismaz 3 punktus).'
            ])->withInput();
        }

        Polygon::create([
            'name'        => $data['name'],
            'coordinates' => $coords,               // saved as array; model cast stores clean JSON
            'user_id'     => $request->user()->id,
            'group_id'    => $data['group_id'],
        ]);

        return back()->with('success', 'Polygon saved!');
    }

    /**
     * Show a single polygon (policy: owner OR same group).
     * Renders map.index with a focused polygon so only that one is drawn.
     */
    public function show(Request $request, Polygon $polygon)
    {
        $this->authorize('view', $polygon);

        $geo = $polygon->toGeoJson();
        abort_unless($geo, 422, 'Invalid polygon');

        $user      = $request->user();
        $waypoints = $user->waypoints()->get();
        $polygons  = Polygon::visibleTo($user)->get();

        // You can reuse the same view as /map; your Blade uses window.focusPolygon already.
        return view('maps.index', [
            'waypoints' => $waypoints,
            'polygons'  => $polygons,
            'focus'     => ['geojson' => $geo],
        ]);
    }

    /**
     * Map page: return visible polygons/waypoints; optionally focus via ?polygon=ID
     */
    public function userPolygons(Request $request)
    {
        $user      = $request->user();
        $polygons  = Polygon::visibleTo($user)->get();
        $waypoints = $user->waypoints()->get();

        $focus = null;

        if ($request->filled('polygon')) {
            // If you switch to slugs/uuid later, this still works if getRouteKeyName() is set.
            $polygon = Polygon::query()->findOrFail($request->polygon);
            $this->authorize('view', $polygon);

            $geo = $polygon->toGeoJson();
            abort_unless($geo, 422, 'Invalid polygon');

            $focus = ['geojson' => $geo];
        }

        // IMPORTANT: use the SAME view your Blade snippet lives in.
        // Your snippet looked like resources/views/map/index.blade.php
        return view('maps.index', compact('polygons', 'waypoints', 'focus'));
    }

    /**
     * Download this polygon as a GeoJSON file.
     */
    public function download(Request $request, Polygon $polygon)
    {
        $this->authorize('view', $polygon);

        $geo = $polygon->toGeoJson();
        abort_unless($geo, 422, 'Invalid polygon');

        $name = preg_replace('/[^\p{L}\p{N}_-]+/u', '_', $polygon->name) ?: 'polygon';
        $json = json_encode($geo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return response($json, 200, [
            'Content-Type'           => 'application/geo+json; charset=utf-8',
            'Content-Disposition'    => "attachment; filename=\"{$name}.geojson\"",
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
