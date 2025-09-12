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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDMO7_a2R-JgJLV2gvV7n9Q7nBOlZq1114&callback=initMap&v=weekly" async defer></script>

    <script>
        // Initialize the map after everything has loaded
        function loadMap() {
            const map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 56.9496, lng: 24.1052 }, // Rīga
                zoom: 7,
                mapTypeId: "terrain"
            });

            // Click to place marker
            map.addListener("click", function(e) {
                placeMarker(e.latLng);
            });

            // Load existing waypoints
            @foreach($waypoints as $waypoint)
                new google.maps.Marker({
                    position: { lat: {{ $waypoint->latitude }}, lng: {{ $waypoint->longitude }} },
                    map: map,
                    title: "{{ $waypoint->name }}"
                });
            @endforeach

            // Global function for placing markers
            window.placeMarker = function(latLng) {
                new google.maps.Marker({
                    position: latLng,
                    map: map
                });
                document.getElementById("latitude").value = latLng.lat();
                document.getElementById("longitude").value = latLng.lng();
            }
        }

        // Wait until Google Maps script and page are loaded
        window.addEventListener('load', function() {
            if (typeof google !== 'undefined') {
                loadMap();
            } else {
                console.error('Google Maps did not load.');
            }
        });
    </script>
</x-app-layout>
