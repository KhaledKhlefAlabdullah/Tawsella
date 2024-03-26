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
                            <p>{{$totalDrivers}}</p>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-6 card"style="padding: 10px;">
                        <div class="text-center card-content">
                            <h4>عدد السيارات</h4>
                            <p>{{ $totalTaxi }}</p>
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
                                <iframe width="600" height="450" frameborder="0" style="border:0"
                                    src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d3523.0238833428734!2d{{ $lifeTaxiMovement->long }}85!3d{{ $lifeTaxiMovement->lat }}!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sar!2str!4v1711312086781!5m2!1sar!2str"
                                    allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>

                                <hr>
                                <form action="{{ route('accept-reject-request',['taxiMovement' => $lifeTaxiMovement->id]) }}" method="POST">
                                    @csrf <!-- Add CSRF token for Laravel form submission -->

                                    <div class="form-group">
                                        <label for="decision" class="form-label">القرار:</label><br>
                                        <select id="decision" name="state" class="form-input" required>
                                            <option value="accept">قبول</option>
                                            <option value="reject">رفض</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="reason" class="form-label">السبب (في حال الرفض):</label><br>
                                        <textarea id="reason" name="message" class="form-input" rows="4" cols="50"></textarea>
                                    </div>
                                    {{-- todo fix this tomoro --}}
                                    <input type="submit" value="Submit" class="form-submit">
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>

            </div>
        </section>
    </main>
@endsection
