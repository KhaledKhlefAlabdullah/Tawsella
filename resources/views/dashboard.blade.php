@extends('layouts.app')

@section('content')
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

    <div class="container-fluid py-4">
        <div class="row">
            <!-- البطاقة الأولى -->
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">weekend</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">مبالغ الطلبات</p>
                            <h4 class="mb-0"> {{ $calculations }} LT</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- البطاقة الثانية -->
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">الطلبات المكتملة</p>
                            <h4 class="mb-0">{{ $requests }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- البطاقة الثالثة -->
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">عدد السائقين</p>
                            <h4 class="mb-0">{{ $totalDrivers }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- البطاقة الرابعة -->
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">weekend</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">عدد السيارات</p>
                            <h4 class="mb-0">{{ $totalTaxi }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- قسم عرض الطلبات والخرائط -->
        <div class="row">
            {{-- الطلبات القادمة --}}
            <div class="col-lg-3 col-md-12 mt-3">
                <ul class="nav nav-tabs w-100" id="myTab" role="tablist">
                    <li class="nav-item w-100" role="presentation">
                        <button class="nav-link active w-100 text-center" id="orders-tab" data-bs-toggle="tab"
                            data-bs-target="#orders" type="button" role="tab" aria-controls="orders"
                            aria-selected="true">الطلبات القادمة</button>
                    </li>
                </ul>
                <div style="max-height: 62vh; overflow-y: auto; scroll-behavior: smooth;">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                            @foreach ($lifeTaxiMovements as $lifeTaxiMovement)
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <h5 class="card-title">تفاصيل الطلب</h5>
                                        <p class="card-text">العميل: {{ $lifeTaxiMovement->customer_name }}</p>
                                        <p class="card-text">من: {{ $lifeTaxiMovement->from }}</p>
                                        <p class="card-text">إلى: {{ $lifeTaxiMovement->to }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- قسم خرائط جوجل -->
            <div class="col-lg-9 col-md-12 mt-3">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">

                <!-- إضافة عنصر اللودينغ -->
                <div id="map-container" style="position: relative;">
                    <div id="loading-spinner"
                        style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading map...</span>
                        </div>
                        <p style="text-align: center;">جارٍ تحميل الخريطة...</p>
                    </div>
                    <div id="map" style="height: 425px; width: 100%;"></div>
                </div>

                <style>
                    @media (max-width: 768px) {
                        #map {
                            height: 300px;
                        }
                    }

                    @media (min-width: 769px) and (max-width: 1024px) {
                        #map {
                            height: 400px;
                        }
                    }
                </style>

                <!-- خرائط جوجل -->
                <script async defer
                    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCz7MVXwh_VtjqnPh5auan0QCVwVce2JX0&callback=initMap"></script>

                <script>
                    function handleMapError() {
                        var loadingSpinner = document.getElementById('loading-spinner');
                        loadingSpinner.style.display = 'none';
                        alert('فشل تحميل الخريطة. يرجى التحقق من اتصال الإنترنت أو صحة مفتاح API.');
                    }

                    function initMap() {
                        var loadingSpinner = document.getElementById('loading-spinner');
                        loadingSpinner.style.display = 'block';

                        var mapElement = document.getElementById('map');
                        if (!mapElement) {
                            console.error('Map element not found');
                            return;
                        }

                        var location = {
                            lat: -34.397,
                            lng: 150.644
                        };

                        try {
                            var map = new google.maps.Map(mapElement, {
                                zoom: 8,
                                center: location
                            });

                            var marker = new google.maps.Marker({
                                position: location,
                                map: map
                            });

                            google.maps.event.addListenerOnce(map, 'tilesloaded', function() {
                                loadingSpinner.style.display = 'none';
                            });
                        } catch (error) {
                            handleMapError();
                        }
                    }
                </script>
            </div>
        </div>
    </div>
@endsection
