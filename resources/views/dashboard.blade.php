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
                        lifeTaxiMovements<li>
                            <div class="card">
                                <h2>طلب جديد</h2>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6 mb-6">
                                        <div class="text-center card-content" style="margin: 10px;">
                                            <h4>اسم العميل: </h4>
                                            {{-- <h4>${customer.name}</h4> --}}
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-6 mb-6">
                                        <div class="text-center card-content" style="margin: 10px;">
                                          <h4>صورة العميل: </h4><h4><img src="${customer.user_avatar}" alt="صورة العميل"/></h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-6">
                                        <div class="text-center card-content" style="margin: 10px;">
                                          <h4>رقم العميل: </h4><h4>${customer.phoneNumber}</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-6">
                                        <div class="text-center card-content" style="margin: 10px;">
                                          <h4>جنس العميل: </h4><h4>${gender}</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-6">
                                        <div class="text-center card-content" style="margin: 10px;">
                                          <h4>عنوان العميل: </h4><h4>${customer_address}</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-6" >
                                        <div class="text-center card-content" style="margin: 10px;">
                                          <h4>وجهة العميل: </h4><h4>${destnation_address}</h4>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13035.963879898062!2d${locationLong}!3d${locationLat}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2598f267cb08b%3A0xae4b9b4fc4d9dc07!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sin!4v1608576658584!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                                <hr> --}}
                             
                            </div>
                          </li>
                    @endforeach

                </ul>
            </div>
        </section>
    </main>
@endsection
