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

    <!-- تصميم لوحة القيادة الجديدة -->
    {{-- <div class="container-fluid py-4">
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
                            <h4 class="mb-0">$53k</h4>
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
                            <h4 class="mb-0">2300</h4>
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
                            <h4 class="mb-0">3462</h4>
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
                            <h4 class="mb-0">103,430</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- قسم عرض الطلبات والخرائط -->
        <div class="row mt-4" style="height: 64vh">
            <div class="col-lg-3 col-md-12 mt-3 mb-3">
                <!-- Tabs navigation -->
                <ul class="nav nav-tabs w-100" id="myTab" role="tablist">
                    <li class="nav-item w-100" role="presentation">
                        <button class="nav-link active w-100 text-center" id="orders-tab" data-bs-toggle="tab"
                            data-bs-target="#orders" type="button" role="tab" aria-controls="orders"
                            aria-selected="true">
                            الطلبات القادمة
                        </button>
                    </li>
                </ul>

                <!-- محتوى التبويبات مع التمرير -->
                <div style="max-height: 62vh; overflow-y: auto; scroll-behavior: smooth;">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                            <!-- عرض الطلبات هنا -->
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
            <div class="col-lg-9 col-md-12 mt-9 mb-9">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Google Maps</title>
                <style>
                    #map {
                        height: 500px;
                        width: 100%;
                    }

                    @media (max-width: 768px) {
                        #map {
                            height: 300px;
                        }
                    }
                </style>

                <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}"></script>
                <script>
                    function initMap() {
                        var mapElement = document.getElementById('map');
                        if (!mapElement) {
                            console.error('Map element not found');
                            return;
                        }
                        var location = {
                            lat: -34.397,
                            lng: 150.644
                        };
                        var map = new google.maps.Map(mapElement, {
                            zoom: 8,
                            center: location
                        });

                        var marker = new google.maps.Marker({
                            position: location,
                            map: map
                        });
                    }
                </script>
                <h1 class="text-center">Google Map Integration</h1>
                <div id="map"></div>
            </div>
        </div>
    </div> --}}
@endsection
