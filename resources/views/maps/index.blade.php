<x-app-layout>
    <section class="bg-gray-900 min-h-screen py-6">
        <div class="max-w-7xl mx-auto px-6">

            <h1 class="text-3xl font-bold text-white mb-6">Medību karte</h1>

            {{-- Map Container --}}
            <div id="map" class="w-full h-[600px] rounded-lg shadow-lg mb-6"></div>

            {{-- Waypoint Form --}}
            <form id="waypointForm" method="POST" action="{{ route('waypoints.store') }}" class="mt-4 space-y-4">
                @csrf
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                <div>
                    <label class="block text-white font-semibold">Nosaukums</label>
                    <input type="text" name="name"
                        class="w-full bg-gray-800 text-white rounded p-2 border border-gray-700 focus:ring-emerald-500 focus:border-emerald-500"
                        required>
                </div>

                <div>
                    <label class="block text-white font-semibold">Apraksts (nav obligāts)</label>
                    <textarea name="description"
                        class="w-full bg-gray-800 text-white rounded p-2 border border-gray-700 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                </div>

                <button class="px-4 py-2 bg-emerald-600 rounded text-white hover:bg-emerald-700">
                    Saglabāt punktu
                </button>
            </form>

            {{-- Polygon Save Form --}}
            <form id="polygonForm" method="POST" action="{{ route('polygons.store') }}" class="mt-6 space-y-4">
                @csrf
                <input type="hidden" name="coordinates" id="polygonCoordinates">

                <div>
                    <label class="block text-white font-semibold">Polygon Name</label>
                    <input type="text" name="name"
                        class="w-full bg-gray-800 text-white rounded p-2 border border-gray-700 focus:ring-emerald-500 focus:border-emerald-500"
                        required>
                </div>

                <button type="submit" class="px-4 py-2 bg-emerald-600 rounded text-white hover:bg-emerald-700">
                    Save Polygon
                </button>
            </form>

            {{-- Existing Waypoints --}}
            <ul class="space-y-2 mt-6">
                @foreach($waypoints as $waypoint)
                    <li class="bg-gray-800 text-white p-3 rounded">
                        <span>{{ $waypoint->name }} ({{ $waypoint->latitude }}, {{ $waypoint->longitude }})</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- Google Maps API with Drawing Library --}}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1AR6ByWMwhdq_M4Xv3-nJXh4GBecWdlA&libraries=drawing&v=weekly&map_ids=8c002f2c7af3d5392f4d5e45" async defer></script>

    <script>
        async function initMap() {
            const { Map } = await google.maps.importLibrary("maps");
            const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

            // Initialize map
            const map = new Map(document.getElementById("map"), {
                center: { lat: 56.9496, lng: 24.1052 },
                zoom: 7,
                mapId: "8c002f2c7af3d5392f4d5e45",
            });

            // Add existing waypoints
            @foreach($waypoints as $waypoint)
                new AdvancedMarkerElement({
                    position: { lat: {{ $waypoint->latitude }}, lng: {{ $waypoint->longitude }} },
                    map,
                    title: "{{ $waypoint->name }}"
                });
            @endforeach

            // Live user location
            if (navigator.geolocation) {
                let userMarker = null;

                navigator.geolocation.watchPosition(
                    (pos) => {
                        const userPos = {
                            lat: pos.coords.latitude,
                            lng: pos.coords.longitude,
                        };

                        if (!userMarker) {
                            const markerDiv = document.createElement("div");
                            markerDiv.style.width = "16px";
                            markerDiv.style.height = "16px";
                            markerDiv.style.backgroundColor = "#4285F4";
                            markerDiv.style.border = "2px solid #fff";
                            markerDiv.style.borderRadius = "50%";

                            userMarker = new AdvancedMarkerElement({
                                position: userPos,
                                map,
                                title: "Your Location",
                                content: markerDiv
                            });

                            map.setCenter(userPos);
                            map.setZoom(14);
                        } else {
                            userMarker.position = userPos;
                        }
                    },
                    (err) => console.error("Geolocation error", err),
                    { enableHighAccuracy: true, maximumAge: 0 }
                );
            } else {
                console.error("Geolocation not supported by this browser.");
            }

            // Click to add waypoint
            map.addListener("click", (e) => {
                new AdvancedMarkerElement({
                    position: e.latLng,
                    map
                });
                document.getElementById("latitude").value = e.latLng.lat();
                document.getElementById("longitude").value = e.latLng.lng();
            });

            // Drawing Manager
            const drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: null,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [
                        google.maps.drawing.OverlayType.POLYGON,
                        google.maps.drawing.OverlayType.POLYLINE
                    ]
                },
                polylineOptions: {
                    strokeColor: "#128720ff",
                    strokeWeight: 3,
                    clickable: true,
                    editable: true,
                    geodesic: true
                },
                polygonOptions: {
                    fillColor: "#128720ff",
                    fillOpacity: 0.2,
                    strokeWeight: 3,
                    strokeColor: "#128720ff",
                    clickable: true,
                    editable: true,
                    zIndex: 1
                }
            });

            drawingManager.setMap(map);

            // Capture drawn shapes
            google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
                let path = [];
                if (event.type === google.maps.drawing.OverlayType.POLYGON) {
                    path = event.overlay.getPath().getArray().map(latlng => ({
                        lat: latlng.lat(),
                        lng: latlng.lng()
                    }));

                    // Save coordinates to hidden input
                    document.getElementById('polygonCoordinates').value = JSON.stringify(path);
                } else if (event.type === google.maps.drawing.OverlayType.POLYLINE) {
                    path = event.overlay.getPath().getArray().map(latlng => ({
                        lat: latlng.lat(),
                        lng: latlng.lng()
                    }));
                    console.log("Polyline coordinates:", path);
                }

                console.log("Coordinates ready to save:", path);
            });
        }

        window.addEventListener("load", initMap);
    </script>
</x-app-layout>
