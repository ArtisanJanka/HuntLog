<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Medību karte</h1>

        {{-- Map Container --}}
        <div id="map" class="w-full h-[600px] rounded-lg shadow-lg mb-6"></div>

        {{-- Waypoint Form --}}
        <form id="waypointForm" method="POST" action="{{ route('waypoints.store') }}" class="mt-4 space-y-4">
            @csrf
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">

            <div>
                <label class="block text-gray-700 font-semibold">Nosaukums</label>
                <input type="text" name="name" class="w-full bg-gray-100 text-gray-900 rounded p-2 border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500" required>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold">Apraksts (nav obligāts)</label>
                <textarea name="description" class="w-full bg-gray-100 text-gray-900 rounded p-2 border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
            </div>

            <button class="px-4 py-2 bg-emerald-600 rounded text-white hover:bg-emerald-700">
                Saglabāt punktu
            </button>
        </form>

        {{-- Existing Waypoints --}}
        <h2 class="text-2xl font-semibold text-gray-800 mt-10 mb-4">Jūsu punkti</h2>
        <ul class="space-y-2">
            @foreach($waypoints as $waypoint)
                <li class="bg-gray-200 text-gray-900 p-3 rounded flex justify-between">
                    <span>{{ $waypoint->name }} ({{ $waypoint->latitude }}, {{ $waypoint->longitude }})</span>
                    <a href="#" class="text-emerald-600 hover:underline">Skatīt</a>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Google Maps API --}}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1AR6ByWMwhdq_M4Xv3-nJXh4GBecWdlA&v=weekly&map_ids=8c002f2c7af3d5392f4d5e45" async defer></script>

    <script>
        async function initMap() {
            const { Map } = await google.maps.importLibrary("maps");
            const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

            const map = new Map(document.getElementById("map"), {
                center: { lat: 56.9496, lng: 24.1052 }, // Riga
                zoom: 7,
                mapId: "8c002f2c7af3d5392f4d5e45", // replace with your actual Map ID
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

                // Create DOM node for blue dot
                const createBlueDot = () => {
                    const dot = document.createElement("div");
                    dot.style.width = "16px";
                    dot.style.height = "16px";
                    dot.style.backgroundColor = "#4285F4";
                    dot.style.border = "2px solid #fff";
                    dot.style.borderRadius = "50%";
                    return dot;
                };

                navigator.geolocation.watchPosition(
                    (pos) => {
                        const userPos = { lat: pos.coords.latitude, lng: pos.coords.longitude };

                        if (!userMarker) {
                            userMarker = new AdvancedMarkerElement({
                                position: userPos,
                                map,
                                title: "Your Location",
                                content: createBlueDot()
                            });
                            map.setCenter(userPos);
                            map.setZoom(14);
                        } else {
                            userMarker.position = userPos; // smooth updates
                        }
                    },
                    (err) => console.error("Geolocation error", err),
                    { enableHighAccuracy: true }
                );
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
        }

        window.addEventListener("load", initMap);
    </script>
</x-app-layout>
