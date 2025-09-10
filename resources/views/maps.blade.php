<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-200 mb-4">Medību karte</h1>

        <!-- Map container -->
        <div id="map" class="w-full h-[600px] rounded-lg shadow-lg"></div>
    </div>

    <script>
        let map;
        let markers = [];

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 56.8796, lng: 24.6032 }, // Latvia
                zoom: 7,
                mapTypeId: "hybrid", // Google Earth-like
            });

            // Click to add marker
            map.addListener("click", (event) => {
                addMarker(event.latLng);
            });
        }

        function addMarker(location) {
            const marker = new google.maps.Marker({
                position: location,
                map: map,
                draggable: true,
            });

            // Info window for parameters
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div class="p-2 text-gray-800">
                        <label class="block text-sm">Dzīvnieks:</label>
                        <input type="text" class="border p-1 w-full" placeholder="Piem. alnis">
                        <label class="block text-sm mt-2">Datums:</label>
                        <input type="date" class="border p-1 w-full">
                        <label class="block text-sm mt-2">Piezīmes:</label>
                        <textarea class="border p-1 w-full"></textarea>
                    </div>
                `
            });

            marker.addListener("click", () => {
                infoWindow.open(map, marker);
            });

            markers.push(marker);
        }
    </script>

    <!-- Load Google Maps API -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBY52MMdRsBTUQXMV8fK5YSqZJZrd3LNSQ&callback=initMap"></script>
</x-app-layout>
