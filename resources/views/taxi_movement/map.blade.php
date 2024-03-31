@extends('layouts.app')

@section('content')
    <main id="main" class="main">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div id="map" class="map"></div>

        <script>
            var map = L.map('map').setView([{{ $taxi->lat }},
                {{ $taxi->long }}
            ], 10); // Set the map view with latitude and longitude of the current request
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            var marker = L.marker([{{$taxi->lat}}, {{$taxi->long}}]).addTo(map);

            var userId = <?php echo json_encode(auth()->id()); ?>;
            Echo.private(`TaxiLocation.${userId}`)
                .listen('TaxiLocation', (e) => {
                    // var marker = L.marker([e.lat, e.long]).addTo(map);
                    alert('hello');
                });
            marker.bindPopup(JSON.stringify('{{ $taxi->name }}')).openPopup();
            // You can customize the popup content as needed     
        </script>
        </section>
    </main>
@endsection
