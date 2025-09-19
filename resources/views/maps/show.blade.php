<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">{{ $title ?? 'Map View' }}</h1>
        <div id="map" class="w-full h-[600px] rounded-lg shadow-lg"></div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1AR6ByWMwhdq_M4Xv3-nJXh4GBecWdlA&v=weekly&map_ids=8c002f2c7af3d5392f4d5e45" async defer></script>

    <script>
        async function initMap() {
            const { Map } = await google.maps.importLibrary("maps");
            const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

            const waypoints = @json($waypoints ?? []);
            const polygons = @json($polygons ?? []);

            let center = { lat: 56.9496, lng: 24.1052 }; // default Riga

            if (waypoints.length) {
                center = { lat: parseFloat(waypoints[0].latitude), lng: parseFloat(waypoints[0].longitude) };
            } else if (polygons.length && polygons[0].coordinates.length) {
                center = { lat: parseFloat(polygons[0].coordinates[0].lat), lng: parseFloat(polygons[0].coordinates[0].lng) };
            }

            const map = new Map(document.getElementById("map"), {
                center: center,
                zoom: 12,
                mapId: "8c002f2c7af3d5392f4d5e45",
            });

            // Add markers
            waypoints.forEach(wp => {
                new AdvancedMarkerElement({
                    position: { lat: parseFloat(wp.latitude), lng: parseFloat(wp.longitude) },
                    map,
                    title: wp.name
                });
            });

            // Draw polygons
            polygons.forEach(poly => {
                if (typeof poly.coordinates === 'string') {
                    try { poly.coordinates = JSON.parse(poly.coordinates); } catch(e){ poly.coordinates = []; }
                }

                if (poly.coordinates.length) {
                    const path = poly.coordinates.map(c => ({ lat: parseFloat(c.lat), lng: parseFloat(c.lng) }));
                    new google.maps.Polygon({
                        paths: path,
                        strokeColor: "#128720",
                        strokeOpacity: 0.8,
                        strokeWeight: 3,
                        fillColor: "#128720",
                        fillOpacity: 0.2,
                        map: map
                    });
                }
            });
        }

        window.addEventListener("load", initMap);
    </script>
</x-app-layout>
