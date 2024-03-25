@extends('layouts.app')

@section('content')
    <main id="main" class="main">
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
                    <div class="col-lg-6 mb-6 card" style="padding: 10px;">
                        <div class="text-center card-content">
                            <h4>مجموع مبالغ الطلبات</h4>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-6 card"style="padding: 10px;">
                        <div class="text-center card-content">
                            <h4>عدد الطلبات</h4>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-6 card"style="padding: 10px;">
                        <div class="text-center card-content">
                            <h4>عدد السائقين</h4>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-6 card"style="padding: 10px;">
                        <div class="text-center card-content">
                            <h4>عدد السيارات</h4>
                        </div>
                    </div>
                </div>

                <ul id="requests-container">
                    @foreach ($lifeTaxiMovements as $lifeTaxiMovement)
                        <li>
                            <div class="card">
                                <h2>طلب جديد</h2>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6 mb-6">
                                        <div class="text-center card-content" style="margin: 10px;">
                                            <h4>اسم العميل: </h4>
                                            <h4>{{ $lifeTaxiMovement->customer_name }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-6">
                                        <div class="text-center card-content" style="margin: 10px;">
                                            <h4>صورة العميل: </h4>
                                            <h4><img src="{{ $lifeTaxiMovement->avatar }}" alt="صورة العميل" /></h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-6">
                                        <div class="text-center card-content" style="margin: 10px;">
                                            <h4>رقم العميل: </h4>
                                            <h4>{{ $lifeTaxiMovement->customer_phone }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-6">
                                        <div class="text-center card-content" style="margin: 10px;">
                                            <h4>جنس العميل: </h4>
                                            <h4>{{ $lifeTaxiMovement->gender }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-6">
                                        <div class="text-center card-content" style="margin: 10px;">
                                            <h4>عنوان العميل: </h4>
                                            <h4>{{ $lifeTaxiMovement->from }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-6">
                                        <div class="text-center card-content" style="margin: 10px;">
                                            <h4>وجهة العميل: </h4>
                                            <h4>{{ $lifeTaxiMovement->to }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div id="map{{ $loop->index }}" class="map"></div>
                                <hr>
                                <form method="POST" action="{{ route('accept.reject.request', ['taxiMovement' => $lifeTaxiMovement->id]) }}">
                                    @csrf <!-- Add CSRF token for Laravel form submission -->
                                
                                    <div class="form-group">
                                        <label for="decision" class="form-label">القرار:</label><br>
                                        <select id="decision" name="state" class="form-input" required>
                                            <option value="accept" {{ old('state') == 'accept' ? 'selected' : '' }}>قبول</option>
                                            <option value="reject" {{ old('state') == 'reject' ? 'selected' : '' }}>رفض</option>
                                        </select>
                                    </div>
                                
                                    <div class="form-group">
                                        <label for="driver_id" class="form-label">اسم السائق:</label><br>
                                        <select id="driver_id" name="driver_id" class="form-input" required>
                                            <option value="">اختر السائق</option>
                                            @foreach($drivers as $driver)
                                                <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                
                                    <div class="form-group">
                                        <label for="reason" class="form-label">السبب (في حال الرفض):</label><br>
                                        <textarea id="reason" name="message" class="form-input" rows="4" cols="50">{{ old('message') }}</textarea>
                                    </div>
                                
                                    <input type="submit" class="form-submit">
                                </form>
                                
                            </div>
                            <script>
                                console.log('hhhhi');
                                var map{{ $loop->index }} = L.map('map{{ $loop->index }}').setView([{{ $lifeTaxiMovement->lat }},
                                    {{ $lifeTaxiMovement->long }}
                                ], 10); // Set the map view with latitude and longitude of the current request
                                console.log('hhhhi');
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                }).addTo(map{{ $loop->index }});
                                console.log('hhhhi');
                                var marker{{ $loop->index }} = L.marker([{{ $lifeTaxiMovement->lat }}, {{ $lifeTaxiMovement->long }}]).addTo(
                                    map{{ $loop->index }});
                                marker{{ $loop->index }}.bindPopup(
                                    {{ $lifeTaxiMovement->customer_name }}); // You can customize the popup content as needed
                            </script>
                        </li>
                    @endforeach
                </ul>

            </div>
        </section>
    </main>
    @foreach ($lifeTaxiMovements as $lifeTaxiMovement)
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
        </script>
    @endforeach
@endsection
