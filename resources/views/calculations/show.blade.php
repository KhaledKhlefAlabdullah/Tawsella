<!-- resources/views/calculations/index.blade.php -->
@extends('layouts.app')

@section('content')
    <main class="main" id="main">
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
      
        <div class="container">
            <h1 class="my-4">عرض الحساب</h1>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Revenue Card -->
                        <div class="col-xxl-6 col-md-6">
                            <div class="card info-card revenue-card">
                                <div class="card-body">
                                    <h5 class="card-title">مبالغ طلبات السائق <span>| مجموع </span></h5>
    
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-currency-dollar"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>LT{{ $details['totalMount'] }}</h6>
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
                                    <h5 class="card-title">الطلبات المكتملة للسائق<span>| عدد</span></h5>
    
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-bookmark-star-fill"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $details['driverMovements'] }}</h6>
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
                                    <h5 class="card-title">المسافة التي قطعها السائق <span>| المسافة</span></h5>
    
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $details['totalWay'] }}</h6>
                                            <hr>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
    
                        </div><!-- End Customers Card -->
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
