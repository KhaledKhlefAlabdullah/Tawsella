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
                    <!-- Revenue Card -->
                    <div class="col-xxl-6 col-md-6">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">مبالغ الطلبات <span>| مجموع </span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-currency-dollar"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>LT {{ $calcolations }}</h6>
                                        <hr>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Revenue Card -->
                    <!-- Customers Card -->
                    <div class="col-xxl-6 col-xl-6">

                        <div class="card info-card customers-card">

                            <div class="card-body">
                                <h5 class="card-title">الطلبات المكتملة <span>| عدد</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-bookmark-star-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $requests }}</h6>
                                        <hr>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div><!-- End Customers Card -->

                    <!-- Customers Card -->
                    <div class="col-xxl-6 col-xl-6">

                        <div class="card info-card customers-card">

                            <div class="card-body">
                                <h5 class="card-title">السائقين <span>| عدد</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $totalDrivers }}</h6>
                                        <hr>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div><!-- End Customers Card -->

                    <!-- Customers Card -->
                    <div class="col-xxl-6 col-xl-6">

                        <div class="card info-card customers-card">

                            <div class="card-body">
                                <h5 class="card-title">السيارات <span>| عدد</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-taxi-front-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $totalTaxi }}</h6>
                                        <hr>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div><!-- End Customers Card -->
                </div>

                <ul id="requests-container">
                    @foreach ($lifeTaxiMovements as $lifeTaxiMovement)
                        <li id='item{{ $loop->index }}'>
                            <div class="card">
                                <h2>طلب جديد</h2>
                                <hr>
                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="col-lg-6 mb-6">
                                            <div class="text-center card-content" style="margin: 10px;">
                                                <h4>العميل: {{ $lifeTaxiMovement->customer_name }}</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-6">
                                            <div class="text-center card-content" style="margin: 10px;">
                                                <img class="img" src="{{ asset('assets' . $lifeTaxiMovement->avatar) }}"
                                                    alt="صورة العميل" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center ">
                                        <div class="col-lg-6 mb-6">
                                            <div class="text-center card-content" style="margin: 10px;">
                                                <h4>رقم العميل: {{ $lifeTaxiMovement->customer_phone }}</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-6">
                                            <div class="text-center card-content" style="margin: 10px;">
                                                <h4>الجنس: <span
                                                        style="color: {{ $lifeTaxiMovement->gender == 'male' ? '#4154f1' : 'pink' }}">{{ $lifeTaxiMovement->gender == 'male' ? 'ذكر' : 'انثى' }}</span>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-6 mb-6">
                                            <div class="text-center card-content" style="margin: 10px;">
                                                <h4>عنوان العميل: {{ $lifeTaxiMovement->from }}</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-6">
                                            <div class="text-center card-content" style="margin: 10px;">
                                                <h4>وجهة العميل: {{ $lifeTaxiMovement->to }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-lg-6 mb-6">
                        
                                    <div class="text-center card-content" style="margin: 10px;">
                                        <h4>التوقيت: {{ date('Y-m-d -- h:i A', strtotime($lifeTaxiMovement->time))}}</h4>
                                    </div>
                                </div>
                                <hr>
                                <div id="map{{ $loop->index }}" class="map"></div>
                                <hr>

                                <div id="buttons{{ $loop->index }}" class="p-4 w-100" style="display: block;">
                                    <button id='accept{{ $loop->index }}' class="btn rounded btn-success"
                                        onclick="showAcceptForm({{ $loop->index }})">قبول</button>
                                    <button id='reject{{ $loop->index }}' class="btn rounded btn-danger "
                                        onclick="showRejectForm({{ $loop->index }})">رفض</button>
                                </div>
                                <button id='cancel{{ $loop->index }}' class="btn" style="display: none;"
                                    onclick="showButtons({{ $loop->index }})"><i class="fa-solid fa-x"></i></button>
                                <form id="accept-form{{ $loop->index }}" method="POST"
                                    action="{{ route('accept.reject.request', ['id' => $lifeTaxiMovement->id]) }}"
                                    style="display: none;">
                                    @csrf <!-- Add CSRF token for Laravel form submission -->
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div id="driver-field{{ $loop->index }}" class="form-group">
                                        <label for="driver_id" class="form-label">اسم السائق:</label><br>
                                        <select id="driver_id{{ $loop->index }}" name="driver_id" class="form-input"
                                            required>
                                            <option value="">اختر السائق</option>
                                            @foreach ($drivers as $driver)
                                                @if ($driver->gender == $lifeTaxiMovement->gender)
                                                    <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Hidden input field for static state -->
                                    <input type="hidden" name="state" value="accepted">
                                    <input type="submit" class="form-submit">
                                </form>

                                <form id="reject-form{{ $loop->index }}" method="POST"
                                    action="{{ route('accept.reject.request', ['id' => $lifeTaxiMovement->id]) }}"
                                    style="display: none;">
                                    @csrf <!-- Add CSRF token for Laravel form submission -->
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div id="reason-field{{ $loop->index }}" class="form-group">
                                        <label for="reason" class="form-label">السبب:</label><br>
                                        <textarea id="reason{{ $loop->index }}" name="message" class="form-input" rows="4" cols="50" required>{{ old('message') }}</textarea>
                                    </div>
                                    <!-- Hidden input field for static state -->
                                    <input type="hidden" name="state" value="rejected">
                                    <input type="submit" class="form-submit">
                                </form>



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
                    @endforeach
                </ul>

            </div>
        </section>
    </main>
@endsection
