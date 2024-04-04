@extends('layouts.app')

@section('content')
    <main id="main" class="main">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div id="map" class="map"></div>
        @vite('resources/js/app.js')
        <script>
            var map = L.map('map').setView([{{ $data->lat }}, {{ $data->long }}], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
        
            var marker = L.marker([{{ $data->lat }}, {{ $data->long }}]).addTo(map);
            marker.bindPopup('{{ $data->name }}').openPopup();
        
            var driver_id = <?php echo json_encode($data->driver_id); ?>;
            var admin_id = <?php echo json_encode(auth()->id()); ?>;
        
            var prevMarker = marker;
        
            Echo.private(`TaxiLocation.${admin_id}`)
                .listen('.App\\Events\\GetTaxiLocationsEvent', (e) => {
                    console.log(e.lat + ',' + e.long);
                    if (e.driver_id == driver_id) {
                        // Remove previous marker
                        map.removeLayer(prevMarker);
        
                        // Add new marker with updated position
                        var newMarker = L.marker([e.lat, e.long]).addTo(map);
                        newMarker.bindPopup('{{ $data->name }}').openPopup();
        
                        // Update prevMarker reference
                        prevMarker = newMarker;
                    }
                });
        </script>
        

        </section>
    </main>
@endsection
