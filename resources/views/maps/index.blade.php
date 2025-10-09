<x-app-layout>
    <section class="relative min-h-screen pt-20 sm:pt-24 overflow-hidden">
        {{-- Background + fog --}}
        <div class="absolute inset-0 bg-gradient-to-b from-black via-gray-900 to-black"></div>
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="fog fog-1"></div>
            <div class="fog fog-2"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10" data-reveal-group>
            <h1 class="reveal text-3xl sm:text-4xl font-black tracking-tight text-white text-center sm:text-left">
                Medību karte
            </h1>

            {{-- Map Card --}}
            <div class="reveal mt-6 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl overflow-hidden">
                <div class="relative">

                    {{-- ===== NEW: Single Responsive Toolbar (LTR) ===== --}}
                    <div class="map-controls pointer-events-none">
                        <div class="map-toolbar pointer-events-auto no-scrollbar">
                            <button id="btn-live" class="tool-btn tool-btn--primary" title="Dzīvā atrašanās vieta">
                                <svg viewBox="0 0 24 24" class="icon"><path d="M12 3a9 9 0 1 0 9 9h-2a7 7 0 1 1-7-7V3Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <span>Dzīvā</span>
                            </button>

                            <button id="btn-follow" class="tool-btn" title="Sekot">
                                <svg viewBox="0 0 24 24" class="icon"><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/><circle cx="12" cy="12" r="3"/></svg>
                                <span>Sekot</span>
                            </button>

                            <button id="btn-locate" class="tool-btn" title="Mana atrašanās (1x)">
                                <svg viewBox="0 0 24 24" class="icon"><path d="M12 2v3m0 14v3m9-9h-3M6 12H3m13.66 6.66L18.5 21.5M5.5 2.5l2.84 2.84M18.5 2.5 15.66 5.34M8.34 18.66 5.5 21.5"/><circle cx="12" cy="12" r="4"/></svg>
                                <span>1x</span>
                            </button>

                            <div class="sep" aria-hidden="true"></div>

                            <button id="btn-waypoint" class="tool-btn" title="Pievienot punktu">
                                <svg viewBox="0 0 24 24" class="icon"><path d="M12 21s7-5.33 7-11a7 7 0 1 0-14 0c0 5.67 7 11 7 11Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                                <span>Punkts</span>
                            </button>

                            <button id="btn-polygon" class="tool-btn" title="Zīmēt poligonu">
                                <svg viewBox="0 0 24 24" class="icon"><path d="M7 3h10l4 6-8 12L3 9 7 3Z"/></svg>
                                <span>Poligons</span>
                            </button>

                            <div class="sep" aria-hidden="true"></div>

                            <button id="btn-fit" class="tool-btn" title="Pielāgot skatu">
                                <svg viewBox="0 0 24 24" class="icon"><path d="M3 9V3h6M21 9V3h-6M3 15v6h6M21 15v6h-6"/></svg>
                                <span>Fit</span>
                            </button>

                            <button id="btn-clear" class="tool-btn tool-btn--danger" title="Notīrīt zīmējumu">
                                <svg viewBox="0 0 24 24" class="icon"><path d="M3 6h18M8 6l1-2h6l1 2m-1 0-1.2 12.4A2 2 0 0 1 13.8 20H10.2a2 2 0 0 1-1.99-1.6L7 6"/></svg>
                                <span>Notīrīt</span>
                            </button>
                        </div>
                    </div>
                    {{-- ===== /toolbar ===== --}}

                    {{-- Map --}}
                    <div id="map" class="w-full h-[62vh] sm:h-[68vh]"></div>
                </div>
            </div>

            {{-- Forms --}}
            <div class="reveal mt-8 grid gap-6 md:grid-cols-2">
                {{-- Waypoint Form --}}
                <form id="waypointForm" method="POST" action="{{ route('waypoints.store') }}"
                      class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl p-5 sm:p-6 shadow-xl space-y-4">
                    @csrf
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <div>
                        <label class="block text-white font-semibold mb-1">Nosaukums</label>
                        <input type="text" name="name"
                               class="w-full bg-gray-800/80 text-white rounded-lg p-2.5 border border-gray-700 focus:ring-emerald-500 focus:border-emerald-500 placeholder-gray-400"
                               placeholder="Piem., barotava pie egles" required>
                    </div>
                    <div>
                        <label class="block text-white font-semibold mb-1">Apraksts (nav obligāts)</label>
                        <textarea name="description"
                                  class="w-full bg-gray-800/80 text-white rounded-lg p-2.5 border border-gray-700 focus:ring-emerald-500 focus:border-emerald-500 placeholder-gray-400"
                                  placeholder="Papildu informācija (piekļuve, sezona u.c.)"></textarea>
                    </div>
                    <button class="w-full sm:w-auto px-5 py-2.5 bg-emerald-600 rounded-lg text-white font-semibold hover:bg-emerald-700 shadow-lg shadow-emerald-900/30 focus:ring-2 focus:ring-emerald-500 transition">
                        Saglabāt punktu
                    </button>
                </form>

                {{-- Polygon Form (with Group select) --}}
                <form id="polygonForm" method="POST" action="{{ route('polygons.store') }}"
                      class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl p-5 sm:p-6 shadow-xl space-y-4">
                    @csrf
                    <input type="hidden" name="coordinates" id="polygonCoordinates">

                    <div>
                        <label class="block text-white font-semibold mb-1">Poligona nosaukums</label>
                        <input type="text" name="name"
                               class="w-full bg-gray-800/80 text-white rounded-lg p-2.5 border border-gray-700 focus:ring-emerald-500 focus:border-emerald-500 placeholder-gray-400"
                               placeholder="Piem., 3. kvartāls" required>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-1">Grupa</label>
                        <select name="group_id"
                                class="w-full bg-gray-800/80 text-white rounded-lg p-2.5 border border-gray-700 focus:ring-emerald-500 focus:border-emerald-500"
                                required>
                            @foreach(auth()->user()->groups as $g)
                                <option value="{{ $g->id }}">{{ $g->name }}</option>
                            @endforeach
                        </select>
                        @error('group_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit"
                            class="w-full sm:w-auto px-5 py-2.5 bg-emerald-600 rounded-lg text-white font-semibold hover:bg-emerald-700 shadow-lg shadow-emerald-900/30 focus:ring-2 focus:ring-emerald-500 transition">
                        Saglabāt poligonu
                    </button>
                </form>
            </div>

            {{-- Existing Waypoints --}}
            @if($waypoints->count())
                <div class="reveal mt-8 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl p-5 sm:p-6 shadow-xl">
                    <h2 class="text-white font-bold text-lg mb-3">Esošie punkti</h2>
                    <ul class="space-y-2 max-h-64 overflow-y-auto pr-1">
                        @foreach($waypoints as $waypoint)
                            <li class="bg-gray-800/80 text-white p-3 rounded-lg text-sm sm:text-base border border-gray-700/70 flex items-center justify-between">
                                <span class="truncate">{{ $waypoint->name }}</span>
                                <span class="text-gray-400 ml-3 shrink-0">
                                    ({{ number_format($waypoint->latitude, 5) }}, {{ number_format($waypoint->longitude, 5) }})
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </section>

    {{-- Google Maps API --}}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1AR6ByWMwhdq_M4Xv3-nJXh4GBecWdlA&libraries=drawing&v=weekly&map_ids=8c002f2c7af3d5392f4d5e45" async defer></script>

    {{-- Expose server data to JS --}}
    @php
        $visiblePolygons = $polygons->map(function ($p) {
            return [
                'id'     => $p->id,
                'name'   => $p->name,
                'coords' => is_array($p->coordinates)
                    ? $p->coordinates
                    : json_decode($p->coordinates ?: '[]', true),
            ];
        });

        $visibleWaypoints = $waypoints->map(function ($w) {
            return [
                'id'   => $w->id,
                'name' => $w->name,
                'lat'  => $w->latitude,
                'lng'  => $w->longitude,
            ];
        });
    @endphp

    <script>
      window.visiblePolygons  = @json($visiblePolygons);
      window.visibleWaypoints = @json($visibleWaypoints);
      window.focusPolygon     = @json($focus ?? null); // set by controller when ?polygon=ID
    </script>

    <script>
    window.addEventListener('load', initMap);

    async function initMap() {
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const { Map, Circle } = await google.maps.importLibrary('maps');
        const { AdvancedMarkerElement } = await google.maps.importLibrary('marker');

        const map = new Map(document.getElementById('map'), {
            center: { lat: 56.9496, lng: 24.1052 },
            zoom: 7,
            mapId: '8c002f2c7af3d5392f4d5e45',
            gestureHandling: 'greedy'
        });

        map.data.setStyle({
            strokeColor: '#10b981',
            strokeOpacity: 1,
            strokeWeight: 2,
            fillColor: '#10b981',
            fillOpacity: 0.15
        });

        const waypointMarkers = [];
        (window.visibleWaypoints || []).forEach(wp => {
            waypointMarkers.push(new AdvancedMarkerElement({
                position: { lat: Number(wp.lat), lng: Number(wp.lng) },
                map,
                title: wp.name || 'Waypoint',
                content: buildPin('#10b981')
            }));
        });

        // Helper: [ {lat,lng} ] -> ring [[lng,lat]... closed]
        function toClosedRingLngLat(coords) {
            if (!Array.isArray(coords) || coords.length < 3) return null;
            const ring = coords.map(p => [Number(p.lng), Number(p.lat)]);
            const a = ring[0], b = ring[ring.length - 1];
            if (!a || !b || a[0] !== b[0] || a[1] !== b[1]) ring.push([...ring[0]]);
            return ring;
        }

        // Show focused polygon only (unless you flip this flag)
        const allowDrawAllWhenNoFocus = false;

        if (window.focusPolygon?.geojson) {
            map.data.addGeoJson(window.focusPolygon.geojson);
            try {
                const coords = window.focusPolygon.geojson.features[0].geometry.coordinates[0];
                const bounds = new google.maps.LatLngBounds();
                coords.forEach(([lng, lat]) => bounds.extend({ lat, lng }));
                map.fitBounds(bounds, 40);
            } catch (_) {}
        } else if (allowDrawAllWhenNoFocus) {
            (window.visiblePolygons || []).forEach(p => {
                const ring = toClosedRingLngLat(p.coords);
                if (!ring) return;
                const geojson = {
                    type: 'FeatureCollection',
                    features: [{
                        type: 'Feature',
                        properties: { id: p.id, name: p.name },
                        geometry: { type: 'Polygon', coordinates: [ ring ] }
                    }]
                };
                map.data.addGeoJson(geojson);
            });
        }

        // Polygon click -> show name + fit
        const info = new google.maps.InfoWindow();
        map.data.addListener('click', (e) => {
            const name = e.feature.getProperty('name') || 'Poligons';
            info.setContent(`<strong>${name}</strong>`);
            info.setPosition(e.latLng);
            info.open({ map });

            try {
                const geom = e.feature.getGeometry(); // Polygon
                const path = geom.getAt(0).getArray(); // first ring
                const bounds = new google.maps.LatLngBounds();
                path.forEach(pt => bounds.extend(pt));
                map.fitBounds(bounds, 40);
            } catch (_) {}
        });

        // Fit both markers and drawn polygons
        function fitToWaypointsAndPolys() {
            const bounds = new google.maps.LatLngBounds();
            let hasAny = false;

            waypointMarkers.forEach(m => { bounds.extend(m.position); hasAny = true; });

            map.data.forEach(feature => {
                const geom = feature.getGeometry();
                if (geom && geom.getType && geom.getType() === 'Polygon') {
                    try {
                        const ring = geom.getAt(0).getArray();
                        ring.forEach(pt => bounds.extend(pt));
                        hasAny = true;
                    } catch (_) {}
                }
            });

            if (hasAny) map.fitBounds(bounds, 80);
        }
        if (!window.focusPolygon?.geojson) fitToWaypointsAndPolys();

        // --- LIVE / Drawing / Controls ---
        let liveWatchId = null, userMarker = null, accuracyCircle = null, followMe = true, lastUserPos = null;
        let addingWaypoint = false, currentPolygon = null;
        const liveOverlays = { polygons: [], polylines: [] };

        function buildPin(color = '#10b981') {
            const d = document.createElement('div');
            d.style.width = '14px';
            d.style.height = '14px';
            d.style.borderRadius = '9999px';
            d.style.background = color;
            d.style.boxShadow = `0 0 0 2px ${hexToRgba(color, .5)}`;
            return d;
        }
        function buildPulsePin(color = '#3b82f6') {
            const wrap = document.createElement('div');
            wrap.style.position = 'relative';
            wrap.style.width = wrap.style.height = '14px';
            const core = buildPin(color);
            const ring = document.createElement('span');
            ring.style.position = 'absolute';
            ring.style.inset = '-8px';
            ring.style.borderRadius = '9999px';
            ring.style.background = `radial-gradient(circle, ${hexToRgba(color,.35)} 0%, rgba(0,0,0,0) 60%)`;
            ring.style.animation = 'ping 1.8s ease-out infinite';
            wrap.appendChild(core); wrap.appendChild(ring);
            return wrap;
        }
        function hexToRgba(hex, a=1){
            const h = hex.replace('#','');
            const v = parseInt(h.length===3 ? h.split('').map(c=>c+c).join('') : h, 16);
            const r=(v>>16)&255, g=(v>>8)&255, b=v&255;
            return `rgba(${r},${g},${b},${a})`;
        }

        const btnLive    = document.getElementById('btn-live');
        const btnFollow  = document.getElementById('btn-follow');
        const btnLocate  = document.getElementById('btn-locate');
        const btnWaypoint= document.getElementById('btn-waypoint');
        const btnPolygon = document.getElementById('btn-polygon');
        const btnFit     = document.getElementById('btn-fit');
        const btnClear   = document.getElementById('btn-clear');

        btnFit.addEventListener('click', fitToWaypointsAndPolys);

        btnClear.addEventListener('click', () => {
            liveOverlays.polygons.forEach(p => p.setMap(null));
            liveOverlays.polylines.forEach(l => l.setMap(null));
            liveOverlays.polygons.length = 0;
            liveOverlays.polylines.length = 0;
            currentPolygon = null;
            document.getElementById('polygonCoordinates').value = '';
        });

        function startLive() {
            if (!navigator.geolocation) return alert('Šī ierīce neatbalsta ģeolokāciju.');
            if (liveWatchId !== null) return;
            liveWatchId = navigator.geolocation.watchPosition(onPos, onGeoError, {
                enableHighAccuracy: true, maximumAge: 0, timeout: 20000
            });
            btnLive.classList.add('ring-2','ring-emerald-500');
        }
        function stopLive() {
            if (liveWatchId !== null) { navigator.geolocation.clearWatch(liveWatchId); liveWatchId = null; }
            btnLive.classList.remove('ring-2','ring-emerald-500');
        }
        function onPos(pos) {
            lastUserPos = { lat: pos.coords.latitude, lng: pos.coords.longitude };
            if (!userMarker) {
                userMarker = new AdvancedMarkerElement({ position: lastUserPos, map, title: 'Tava atrašanās vieta', content: buildPulsePin('#3b82f6') });
            } else { userMarker.position = lastUserPos; }
            const radius = Math.max(10, pos.coords.accuracy || 0);
            if (!accuracyCircle) {
                accuracyCircle = new Circle({ map, center: lastUserPos, radius, strokeColor:'#3b82f6', strokeOpacity:.5, strokeWeight:1, fillColor:'#3b82f6', fillOpacity:.08 });
            } else { accuracyCircle.setCenter(lastUserPos); accuracyCircle.setRadius(radius); }
            if (followMe) { map.panTo(lastUserPos); if (map.getZoom() < 14) map.setZoom(14); }
        }
        function onGeoError(err) { console.error('Geolocation error:', err); stopLive(); alert('Neizdevās iegūt atrašanās vietu. Lūdzu pārbaudi atļaujas/servisus.'); }
        btnLive.addEventListener('click', () => { if (liveWatchId === null) startLive(); else stopLive(); });
        btnFollow.addEventListener('click', () => { followMe = !followMe; btnFollow.classList.toggle('ring-2', followMe); btnFollow.classList.toggle('ring-emerald-500', followMe); if (followMe && lastUserPos) map.panTo(lastUserPos); });
        btnLocate.addEventListener('click', () => {
            if (!navigator.geolocation) return;
            navigator.geolocation.getCurrentPosition((pos) => {
                const p = { lat: pos.coords.latitude, lng: pos.coords.longitude };
                if (!userMarker) userMarker = new AdvancedMarkerElement({ position: p, map, content: buildPulsePin('#3b82f6') });
                else userMarker.position = p;
                if (!accuracyCircle) accuracyCircle = new Circle({ map, center: p, radius: pos.coords.accuracy || 0, strokeColor:'#3b82f6', strokeOpacity:.5, strokeWeight:1, fillColor:'#3b82f6', fillOpacity:.08 });
                else { accuracyCircle.setCenter(p); accuracyCircle.setRadius(pos.coords.accuracy || 0); }
                map.setCenter(p); map.setZoom(14);
            }, onGeoError, { enableHighAccuracy:true, maximumAge:0, timeout:20000 });
        });

        // Drawing manager (for creating a polygon)
        const drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: null,
            drawingControl: false,
            polylineOptions: { strokeColor:'#10b981', strokeWeight:3, clickable:true, editable:true, geodesic:true },
            polygonOptions:  { fillColor:'#10b981', fillOpacity:.15, strokeWeight:3, strokeColor:'#10b981', clickable:true, editable:true, zIndex:2 }
        });
        drawingManager.setMap(map);

        btnWaypoint.addEventListener('click', () => {
            addingWaypoint = !addingWaypoint;
            btnWaypoint.classList.toggle('ring-2');
            btnWaypoint.classList.toggle('ring-emerald-500');
            map.setOptions({ draggableCursor: addingWaypoint ? 'crosshair' : undefined });
        });

        map.addListener('click', (e) => {
            if (!addingWaypoint) return;
            const m = new AdvancedMarkerElement({ position: e.latLng, map, content: buildPin('#10b981') });
            waypointMarkers.push(m);
            document.getElementById('latitude').value  = e.latLng.lat();
            document.getElementById('longitude').value = e.latLng.lng();
        });

        btnPolygon.addEventListener('click', () => {
            const active = drawingManager.getDrawingMode() === google.maps.drawing.OverlayType.POLYGON;
            drawingManager.setDrawingMode(active ? null : google.maps.drawing.OverlayType.POLYGON);
            btnPolygon.classList.toggle('ring-2', !active);
            btnPolygon.classList.toggle('ring-emerald-500', !active);
        });

        google.maps.event.addListener(drawingManager, 'overlaycomplete', (evt) => {
            if (evt.type === google.maps.drawing.OverlayType.POLYGON) {
                if (currentPolygon) currentPolygon.setMap(null);
                currentPolygon = evt.overlay;
                liveOverlays.polygons.push(currentPolygon);
                drawingManager.setDrawingMode(null);
                btnPolygon.classList.remove('ring-2','ring-emerald-500');
                syncPolygonPath(currentPolygon);
                writePolygonCoordinates(currentPolygon.getPath());
                google.maps.event.addListener(currentPolygon.getPath(), 'insert_at', () => writePolygonCoordinates(currentPolygon.getPath()));
                google.maps.event.addListener(currentPolygon.getPath(), 'set_at',    () => writePolygonCoordinates(currentPolygon.getPath()));
            } else if (evt.type === google.maps.drawing.OverlayType.POLYLINE) {
                liveOverlays.polylines.push(evt.overlay);
            }
        });

        function writePolygonCoordinates(path){
            const coords = path.getArray().map(latlng => ({ lat: latlng.lat(), lng: latlng.lng() }));
            document.getElementById('polygonCoordinates').value = JSON.stringify(coords);
        }
        function syncPolygonPath(poly){
            poly.setOptions({ editable:true, fillColor:'#10b981', fillOpacity:.15, strokeColor:'#10b981', strokeWeight:3 });
        }

        // Reveal stagger
        if (!prefersReduced) {
            const groupObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) return;
                    const items = entry.target.querySelectorAll('.reveal');
                    items.forEach((el, i) => {
                        el.style.transitionDelay = `${i * 110}ms`;
                        el.classList.add('show');
                    });
                    groupObserver.unobserve(entry.target);
                });
            }, { threshold: 0.15 });
            document.querySelectorAll('[data-reveal-group]').forEach(g => groupObserver.observe(g));
        } else {
            document.querySelectorAll('.reveal').forEach(el => el.classList.add('show'));
        }
    }
    </script>

    {{-- Styles (fog + reveal + toolbar) --}}
    <style>
    .fog {
        position:absolute; width:40vw; height:40vw; min-width:360px; min-height:360px;
        background: radial-gradient(circle, rgba(255,255,255,.07) 0%, transparent 60%);
        filter: blur(60px); opacity:.28; transform: translateZ(0);
        animation: fogDrift 34s ease-in-out infinite;
    }
    .fog-1{ top:10%; left:-12%; }
    .fog-2{ bottom:-10%; right:-8%; animation-duration:40s; opacity:.24; }

    [data-reveal-group] .reveal { opacity:0; transform: translateY(14px) scale(.98); transition: opacity .6s ease, transform .6s ease; }
    [data-reveal-group] .reveal.show { opacity:1; transform: none; }

    @keyframes ping { 0% { transform: scale(.6); opacity:.8 } 100% { transform: scale(2.4); opacity:0 } }
    @keyframes fogDrift { 0%{transform:translate(0,0) scale(1)} 50%{transform:translate(60px,-40px) scale(1.12)} 100%{transform:translate(0,0) scale(1)} }

    .map-controls{ position:absolute; top:1rem; left:1rem; right:1rem; z-index:20; }
    .map-toolbar{
      display:flex; align-items:center; gap:.5rem; flex-wrap:nowrap;
      overflow-x:auto; padding:.5rem .6rem;
      background: rgba(0,0,0,.45);
      border:1px solid rgba(255,255,255,.12);
      backdrop-filter: blur(10px);
      border-radius: .9rem;
      box-shadow: 0 10px 30px rgba(0,0,0,.5);
      filter: saturate(.95) brightness(.95);
    }
    .no-scrollbar{ scrollbar-width:none; }
    .no-scrollbar::-webkit-scrollbar{ display:none; }

    .tool-btn{
      display:inline-flex; align-items:center; gap:.45rem;
      padding:.5rem .7rem; border-radius:.7rem;
      background: rgba(255,255,255,.06);
      color:#e5e7eb; border:1px solid rgba(255,255,255,.12);
      font-weight:600; font-size:.85rem; letter-spacing:.01em;
      transition: all .18s ease;
      white-space:nowrap;
    }
    .tool-btn .icon{ width:18px; height:18px; fill:currentColor; opacity:.9; }
    .tool-btn:hover{ background: rgba(255,255,255,.10); color:#d1fae5; border-color: rgba(16,185,129,.45); }
    .tool-btn:focus{ outline:none; box-shadow: 0 0 0 2px rgba(16,185,129,.45); }

    .tool-btn--primary{ background: rgba(16,185,129,.22); border-color: rgba(16,185,129,.45); color:#d1fae5; }
    .tool-btn--primary:hover{ background: rgba(16,185,129,.28); }

    .tool-btn--danger{ background: rgba(220,38,38,.18); border-color: rgba(248,113,113,.45); color:#fecaca; }
    .tool-btn--danger:hover{ background: rgba(220,38,38,.24); color:#fee2e2; }

    .sep{ width:1px; height:26px; background: linear-gradient(180deg, transparent, rgba(255,255,255,.18), transparent); margin:0 .1rem; }

    @media (max-width: 420px){
      .tool-btn span{ display:none; } /* icon-only on very narrow screens */
    }
    </style>
</x-app-layout>
