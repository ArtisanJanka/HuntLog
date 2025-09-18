<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">{{ $waypoint->name }} on Map</h1>

        <div id="map" class="w-full h-[600px] rounded-lg shadow-lg"></div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1AR6ByWMwhdq_M4Xv3-nJXh4GBecWdlA&v=weekly" async defer></script>

    <script>
        async function initMap() {
            const { Map } = await google.maps.importLibrary("maps");
            const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

            const map = new Map(document.getElementById("map"), {
                center: { lat: {{ $waypoint->latitude }}, lng: {{ $waypoint->longitude }} },
                zoom: 14,
            });

            new AdvancedMarkerElement({
                position: { lat: {{ $waypoint->latitude }}, lng: {{ $waypoint->longitude }} },
                map,
                title: "{{ $waypoint->name }}"
            });
        }

        window.addEventListener("load", initMap);
    </script>
</x-app-layout>
