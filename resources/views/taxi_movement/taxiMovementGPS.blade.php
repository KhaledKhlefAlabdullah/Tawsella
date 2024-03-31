@extends('layouts.app')

@section('content')
    <main id="main" class="main">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="pagetitle">
            <h1>لوحة التحكم</h1>
            <nav>
                <ol class="breadcrumb">
                    <li>
                        <h6> لوحة التحكم / </h6>
                    </li>
                    <li><a href="{{ url('dashboard') }}">الصفحة الرئيسية</a></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="container">
                <div class="row">
                    <!-- Left side columns -->
                    <div class="col-lg-4 mb-4 card" style="padding: 10px;">
                        <div class="text-center card-content">
                            <h4></h4>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 card"style="padding: 10px;">
                        <div class="text-center card-content">
                            <h4>{{ $movement->customer_name }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 card"style="padding: 10px;">
                        <div class="text-center card-content">
                            <h4>{{ $movement->customer_phone }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 card"style="padding: 10px;">
                        <div class="text-center card-content">
                            <h4>{{ $movement->my_address }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 card"style="padding: 10px;">
                        <div class="text-center card-content">
                            <h4>{{ $movement->destnation_address }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 card"style="padding: 10px;">
                        <div class="text-center card-content">
                            <h4>{{ $movement->gender }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 card"style="padding: 10px;">
                        <div class="text-center card-content">
                            <h4>{{ $movement->driver_name }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 card"style="padding: 10px;">
                        <div class="text-center card-content">
                            <h4>{{ $movement->driver_phone }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 card"style="padding: 10px;">
                        <div class="text-center card-content">
                            <h4>{{ $movement->car_lamp_number }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 card"style="padding: 10px;">
                        <div class="text-center card-content">
                            <h4>{{ $movement->car_plate_number }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 card"style="padding: 10px;">
                        <div class="text-center card-content">
                            <h4>{{ $movement->type }}</h4>
                        </div>
                    </div>
                </div>

                <ul id="requests-container">
                    <li id='item{{ $loop->index }}'>
                        <div class="card">
                            <div id="map{{ $loop->index }}" class="map"></div>
                            <hr>
                        </div>
                        <script>
                            var map{{ $loop->index }} = L.map('map{{ $loop->index }}').setView([{{ $lifeTaxiMovement->lat }},
                                {{ $lifeTaxiMovement->long }}
                            ], 10); // Set the map view with latitude and longitude of the current request
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                            }).addTo(map{{ $loop->index }});
                            var marker{{ $loop->index }} = L.marker([{{ $lifeTaxiMovement->lat }}, {{ $lifeTaxiMovement->long }}]).addTo(
                                map{{ $loop->index }});
                            marker{{ $loop->index }}.bindPopup(JSON.stringify('{{ $lifeTaxiMovement->customer_name }}')).openPopup();
                            // You can customize the popup content as needed

                            function showAcceptForm(index) {
                                document.getElementById('accept-form' + index).style.display = 'block';
                                document.getElementById('reject-form' + index).style.display = 'none';
                                document.getElementById('buttons' + index).style.display = 'none';
                                document.getElementById('cancel' + index).style.display = 'block';

                            }

                            function showRejectForm(index) {
                                document.getElementById('accept-form' + index).style.display = 'none';
                                document.getElementById('reject-form' + index).style.display = 'block';
                                document.getElementById('buttons' + index).style.display = 'none';
                                document.getElementById('cancel' + index).style.display = 'block';
                            }

                            function showButtons(index) {
                                document.getElementById('accept-form' + index).style.display = 'none';
                                document.getElementById('reject-form' + index).style.display = 'none';
                                document.getElementById('buttons' + index).style.display = 'block';
                                document.getElementById('cancel' + index).style.display = 'none';
                            }
                        </script>
                    </li>
                </ul>

            </div>
        </section>
    </main>
@endsection
