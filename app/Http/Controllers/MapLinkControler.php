<?php

namespace App\Http\Controllers;

use App\Models\Polygon;
use Illuminate\Http\RedirectResponse;

class MapLinkController extends Controller
{
    public function open(Polygon $polygon): RedirectResponse
    {
        // Route already has 'can:view,polygon', this is extra safety:
        $this->authorize('view', $polygon);

        return redirect()->route('map.index', ['polygon' => $polygon->getRouteKey()]);
    }
}
