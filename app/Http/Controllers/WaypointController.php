<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Waypoint;
use App\Models\Polygon;

class WaypointController extends Controller
{
    /**
     * Map page. Shows user's waypoints and polygons the user can see (owner OR same group).
     * Supports focusing a polygon via ?polygon=ID (authorized via PolygonPolicy::view).
     */
    public function showMap(Request $request)
    {
        $user = $request->user();

        // Owner-only waypoints (unchanged)
        $waypoints = $user->waypoints()->get();

        // Polygons visible to this user (owner OR same group)
        $polygons = Polygon::visibleTo($user)->get();

        // Optional focus: /map?polygon=123
        $focus = null;
        if ($id = $request->integer('polygon')) {
            $polygon = Polygon::findOrFail($id);
            $this->authorize('view', $polygon); // policy: owner OR same group

            // Prefer model helper if present
            $geo = method_exists($polygon, 'toGeoJson') ? $polygon->toGeoJson() : $this->buildGeoJsonFallback($polygon);

            $focus = [
                'id'      => $polygon->id,
                'name'    => $polygon->name,
                'geojson' => $geo,
            ];
        }

        return view('maps.index', compact('waypoints', 'polygons', 'focus'));
    }

    /**
     * Show a single waypoint (owner-only).
     */
    public function show(Waypoint $waypoint)
    {
        $user = auth()->user();
        if ($waypoint->user_id !== $user->id) {
            abort(403);
        }

        return view('maps.show', [
            'waypoints' => [$waypoint],
            'polygons'  => [],
        ]);
    }

    /**
     * (Optional) Show a single polygon on the map.
     * Uses policy to authorize access (owner OR same group).
     * If you prefer, you can just redirect to /map?polygon=ID instead.
     */
    public function showPolygon(Polygon $polygon)
    {
        $this->authorize('view', $polygon);

        $coords = is_array($polygon->coordinates)
            ? $polygon->coordinates
            : json_decode($polygon->coordinates ?? '[]', true);

        return view('maps.show', [
            'waypoints' => [],
            'polygons'  => [[
                'name'        => $polygon->name,
                'coordinates' => $coords,
            ]],
        ]);
    }

    /**
     * Store a polygon (from the map UI). Requires group_id and enforces membership.
     * If you already create polygons in PolygonController@store, you can delete this and the route.
     */
    public function storePolygon(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'coordinates' => 'required|string',        // JSON string from client
            'group_id'    => 'required|exists:groups,id',
        ]);

        // Ensure the user belongs to the chosen group
        abort_unless(
            $request->user()->groups()->whereKey($request->group_id)->exists(),
            403
        );

        Polygon::create([
            'name'        => $request->name,
            'coordinates' => $request->coordinates,
            'user_id'     => $request->user()->id,
            'group_id'    => $request->group_id,
        ]);

        return back()->with('success', 'Polygon saved!');
    }

    /**
     * Fallback GeoJSON builder if the model doesn't have toGeoJson().
     */
    private function buildGeoJsonFallback(Polygon $polygon): ?array
    {
        $coords = is_array($polygon->coordinates)
            ? $polygon->coordinates
            : json_decode($polygon->coordinates ?: '[]', true);

        if (!is_array($coords) || count($coords) < 3) {
            return null;
        }

        // Convert [{lat,lng}] -> [[lng,lat], ...] and close the ring
        $ring = array_map(fn($p) => [(float)($p['lng'] ?? 0), (float)($p['lat'] ?? 0)], $coords);
        if ($ring && ($ring[0] !== end($ring))) {
            $ring[] = $ring[0];
        }

        return [
            'type' => 'FeatureCollection',
            'features' => [[
                'type' => 'Feature',
                'properties' => ['id' => $polygon->id, 'name' => $polygon->name],
                'geometry' => ['type' => 'Polygon', 'coordinates' => [ $ring ]],
            ]],
        ];
    }
}
