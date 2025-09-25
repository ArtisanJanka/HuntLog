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
                    {{-- Custom Controls --}}
                    <div class="absolute top-4 right-4 z-10 flex flex-wrap gap-2">
                        <button id="btn-live"
                                class="px-3 py-2 rounded-md text-sm font-semibold bg-emerald-600 text-white hover:bg-emerald-700 shadow-lg shadow-emerald-900/30 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                            Dzīvā atrašanās vieta
                        </button>
                        <button id="btn-follow"
                                class="px-3 py-2 rounded-md text-sm font-semibold bg-white/10 text-white border border-white/10 hover:border-emerald-400/50 hover:text-emerald-300 hover:bg-white/15 transition">
                            Sekot
                        </button>
                        <button id="btn-locate"
                                class="px-3 py-2 rounded-md text-sm font-semibold bg-white/10 text-white border border-white/10 hover:border-emerald-400/50 hover:text-emerald-300 hover:bg-white/15 transition">
                            Mana atrašanās vieta (1x)
                        </button>
                        <button id="btn-waypoint"
                                class="px-3 py-2 rounded-md text-sm font-semibold bg-white/10 text-white border border-white/10 hover:border-emerald-400/50 hover:text-emerald-300 hover:bg-white/15 transition">
                            Pievienot punktu
                        </button>
                        <button id="btn-polygon"
                                class="px-3 py-2 rounded-md text-sm font-semibold bg-white/10 text-white border border-white/10 hover:border-emerald-400/50 hover:text-emerald-300 hover:bg-white/15 transition">
                            Zīmēt poligonu
                        </button>
                        <button id="btn-fit"
                                class="px-3 py-2 rounded-md text-sm font-semibold bg-white/10 text-white border border-white/10 hover:border-emerald-400/50 hover:text-emerald-300 hover:bg-white/15 transition">
                            Pielāgot skatījumu
                        </button>
                        <button id="btn-clear"
                                class="px-3 py-2 rounded-md text-sm font-semibold bg-white/10 text-white border border-white/10 hover:border-red-400/50 hover:text-red-300 hover:bg-white/15 transition">
                            Notīrīt zīmējumu
                        </button>
                    </div>

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

                {{-- Polygon Form --}}
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

    <script>
    window.addEventListener('load', initMap);

    async function initMap() {
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const { Map, Circle } = await google.maps.importLibrary('maps');
        const { AdvancedMarkerElement } = await google.maps.importLibrary('marker');

        // Map
        const map = new Map(document.getElementById('map'), {
            center: { lat: 56.9496, lng: 24.1052 },
            zoom: 7,
            mapId: '8c002f2c7af3d5392f4d5e45',
            gestureHandling: 'greedy'
        });

        // Collections / state
        const liveOverlays = { polygons: [], polylines: [] };
        const waypointMarkers = [];
        let addingWaypoint = false;
        let currentPolygon = null;

        // LIVE location state
        let liveWatchId = null;
        let userMarker = null;
        let accuracyCircle = null;
        let followMe = true;
        let lastUserPos = null;

        // Build pins
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

        // Existing waypoints -> markers
        @foreach($waypoints as $waypoint)
            waypointMarkers.push(new AdvancedMarkerElement({
                position: { lat: {{ $waypoint->latitude }}, lng: {{ $waypoint->longitude }} },
                map,
                title: "{{ $waypoint->name }}",
                content: buildPin('#10b981')
            }));
        @endforeach

        // Fit bounds to existing waypoints
        function fitToWaypoints() {
            if (!waypointMarkers.length) return;
            const bounds = new google.maps.LatLngBounds();
            waypointMarkers.forEach(m => bounds.extend(m.position));
            map.fitBounds(bounds, 80);
        }

        // --- LIVE LOCATION (watchPosition) ---
        const btnLive = document.getElementById('btn-live');
        const btnFollow = document.getElementById('btn-follow');

        function startLive() {
            if (!navigator.geolocation) {
                alert('Šī ierīce neatbalsta ģeolokāciju.');
                return;
            }
            if (liveWatchId !== null) return; // already running

            liveWatchId = navigator.geolocation.watchPosition(onPos, onGeoError, {
                enableHighAccuracy: true,
                maximumAge: 0,
                timeout: 20000
            });

            // UI on
            btnLive.classList.add('ring-2','ring-emerald-500');
        }

        function stopLive() {
            if (liveWatchId !== null) {
                navigator.geolocation.clearWatch(liveWatchId);
                liveWatchId = null;
            }
            btnLive.classList.remove('ring-2','ring-emerald-500');
        }

        function onPos(pos) {
            lastUserPos = { lat: pos.coords.latitude, lng: pos.coords.longitude };

            if (!userMarker) {
                userMarker = new AdvancedMarkerElement({
                    position: lastUserPos,
                    map,
                    title: 'Tava atrašanās vieta',
                    content: buildPulsePin('#3b82f6') // blue
                });
            } else {
                userMarker.position = lastUserPos;
            }

            // Accuracy circle
            const radius = Math.max(10, pos.coords.accuracy || 0);
            if (!accuracyCircle) {
                accuracyCircle = new Circle({
                    map,
                    center: lastUserPos,
                    radius,
                    strokeColor: '#3b82f6',
                    strokeOpacity: 0.5,
                    strokeWeight: 1,
                    fillColor: '#3b82f6',
                    fillOpacity: 0.08
                });
            } else {
                accuracyCircle.setCenter(lastUserPos);
                accuracyCircle.setRadius(radius);
            }

            if (followMe) {
                map.panTo(lastUserPos);
                if (map.getZoom() < 14) map.setZoom(14);
            }
        }

        function onGeoError(err) {
            console.error('Geolocation error:', err);
            stopLive();
            alert('Neizdevās iegūt atrašanās vietu. Lūdzu pārbaudi atļaujas/servisus.');
        }

        btnLive.addEventListener('click', () => {
            if (liveWatchId === null) startLive(); else stopLive();
        });

        btnFollow.addEventListener('click', () => {
            followMe = !followMe;
            btnFollow.classList.toggle('ring-2', followMe);
            btnFollow.classList.toggle('ring-emerald-500', followMe);
            if (followMe && lastUserPos) map.panTo(lastUserPos);
        });

        // One-shot locate (center once)
        const btnLocate = document.getElementById('btn-locate');
        btnLocate.addEventListener('click', () => {
            if (!navigator.geolocation) return;
            navigator.geolocation.getCurrentPosition((pos) => {
                const p = { lat: pos.coords.latitude, lng: pos.coords.longitude };
                if (!userMarker) {
                    userMarker = new AdvancedMarkerElement({ position: p, map, content: buildPulsePin('#3b82f6') });
                } else {
                    userMarker.position = p;
                }
                if (!accuracyCircle) {
                    accuracyCircle = new Circle({
                        map, center: p, radius: pos.coords.accuracy || 0,
                        strokeColor:'#3b82f6', strokeOpacity:.5, strokeWeight:1,
                        fillColor:'#3b82f6', fillOpacity:.08
                    });
                } else {
                    accuracyCircle.setCenter(p);
                    accuracyCircle.setRadius(pos.coords.accuracy || 0);
                }
                map.setCenter(p); map.setZoom(14);
            }, onGeoError, { enableHighAccuracy:true, maximumAge:0, timeout:20000 });
        });

        // Waypoint mode
        const btnWaypoint = document.getElementById('btn-waypoint');
        btnWaypoint.addEventListener('click', () => {
            addingWaypoint = !addingWaypoint;
            btnWaypoint.classList.toggle('ring-2');
            btnWaypoint.classList.toggle('ring-emerald-500');
            map.setOptions({ draggableCursor: addingWaypoint ? 'crosshair' : undefined });
        });

        map.addListener('click', (e) => {
            if (!addingWaypoint) return;
            const marker = new AdvancedMarkerElement({
                position: e.latLng,
                map,
                content: buildPin('#10b981')
            });
            waypointMarkers.push(marker);
            document.getElementById('latitude').value  = e.latLng.lat();
            document.getElementById('longitude').value = e.latLng.lng();
        });

        // Drawing manager
        const drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: null,
            drawingControl: false,
            polylineOptions: {
                strokeColor: '#10b981',
                strokeWeight: 3,
                clickable: true,
                editable: true,
                geodesic: true
            },
            polygonOptions: {
                fillColor: '#10b981',
                fillOpacity: 0.15,
                strokeWeight: 3,
                strokeColor: '#10b981',
                clickable: true,
                editable: true,
                zIndex: 2
            }
        });
        drawingManager.setMap(map);

        const btnPolygon = document.getElementById('btn-polygon');
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
            poly.setOptions({
                editable: true,
                fillColor: '#10b981',
                fillOpacity: 0.15,
                strokeColor: '#10b981',
                strokeWeight: 3
            });
        }

        // Utility buttons
        const btnFit = document.getElementById('btn-fit');
        btnFit.addEventListener('click', fitToWaypoints);

        const btnClear = document.getElementById('btn-clear');
        btnClear.addEventListener('click', () => {
            liveOverlays.polygons.forEach(p => p.setMap(null));
            liveOverlays.polylines.forEach(l => l.setMap(null));
            liveOverlays.polygons.length = 0;
            liveOverlays.polylines.length = 0;
            currentPolygon = null;
            document.getElementById('polygonCoordinates').value = '';
        });

        // Start with existing points in view
        fitToWaypoints();

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

    {{-- Styles (fog + reveal + ping) --}}
    <style>
    .fog {
        position:absolute; width:40vw; height:40vw; min-width:360px; min-height:360px;
        background: radial-gradient(circle, rgba(255,255,255,.07) 0%, transparent 60%);
        filter: blur(60px); opacity:.28; transform: translateZ(0);
        animation: fogDrift 34s ease-in-out infinite;
    }
    .fog-1{ top:10%; left:-12%; }
    .fog-2{ bottom:-10%; right:-8%; animation-duration:40s; opacity:.24; }
    @keyframes fogDrift { 0%{transform:translate(0,0) scale(1)} 50%{transform:translate(60px,-40px) scale(1.12)} 100%{transform:translate(0,0) scale(1)} }

    [data-reveal-group] .reveal { opacity:0; transform: translateY(14px) scale(.98); transition: opacity .6s ease, transform .6s ease; }
    [data-reveal-group] .reveal.show { opacity:1; transform: none; }

    @keyframes ping { 0% { transform: scale(.6); opacity:.8 } 100% { transform: scale(2.4); opacity:0 } }

    @media (prefers-reduced-motion: reduce) {
        .fog { animation: none; opacity: .2; }
        [data-reveal-group] .reveal { transition: none; opacity: 1 !important; transform: none !important; }
    }
    </style>
</x-app-layout>
