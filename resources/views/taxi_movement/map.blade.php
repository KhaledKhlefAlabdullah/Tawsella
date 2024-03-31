@extends('layouts.app')

@section('content')
    <main id="main" class="main">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div id="map" class="map"></div>
        @vite('resources/js/app.js')
        <script>
            var map = L.map('map').setView([{{ $taxi->lat }}, {{ $taxi->long }}], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var marker = L.marker([{{ $taxi->lat }}, {{ $taxi->long }}]).addTo(map);
            marker.bindPopup(JSON.stringify('{{ $taxi->name }}')).openPopup();

            var driver_id = <?php echo json_encode($taxi->driver_id); ?>;
            var admin_id = <?php echo json_encode(auth()->id()); ?>;

            var prevMarker = marker;

            setTimeout(() => {
                Echo.private(`TaxiLocation.${admin_id}`)
                    .listen('.App\\Events\\GetTaxiLocationsEvent', (e) => {
                        console.log('here');
                        alert('hhhh');
                        if (e.driver_id == driver_id) {
                            // Remove previous marker
                            map.removeLayer(prevMarker);

                            // Add new marker with updated position
                            var newMarker = L.marker([e.lat, e.long]).addTo(map);
                            newMarker.bindPopup(JSON.stringify('{{ $taxi->name }}')).openPopup();

                            // Update prevMarker reference
                            prevMarker = newMarker;
                        }
                    });
            }, 10000);
        </script>

        </section>
    </main>
@endsection
