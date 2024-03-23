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
                    
                </ul>
            </div>
        </section>
    </main>
@endsection
