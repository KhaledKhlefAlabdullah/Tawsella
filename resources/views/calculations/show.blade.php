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
                <div class="container">
                    <div class="row m-4">
                        <!-- Revenue Card -->
                        <div class="col-xxl-6 col-md-6">
                            <div class="card info-card revenue-card">
                                <div class="card-body m-2">
                                    <h5>مبالغ طلبات السائق <span>| مجموع </span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-currency-dollar"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $details['totalMount'] }}</h6>
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Revenue Card -->
                        <!-- Customers Card -->
                        <div class="col-xxl-6 col-xl-6">

                            <div class="card info-card customers-card">

                                <div class="card-body m-2">
                                    <h5>الطلبات المكتملة للسائق<span>| عدد</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
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

                                <div class="card-body m-2">
                                    <h5>المسافة التي قطعها السائق <span>| المسافة</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
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
                        <!-- Customers Card -->
                        <div class="col-xxl-6 col-xl-6">

                            <div class="card info-card customers-card">

                                <div class="card-body m-2">
                                    {{-- <h5 >المسافة التي قطعها السائق <span>| المسافة</span></h5> --}}

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6></h6>
                                            <hr>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div><!-- End Customers Card -->
                    </div>
                </div>
                <div class="container">
                    <h1>الطلبات التي قام بها السائق</h1>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">عنوان البداية </th>
                                <th scope="col">عنوان الوجهة</th>
                                <th scope="col">المسافة</th>
                                <th scope="col">المبلغ الاجمالي</th>
                                <th scope="col">المسافة</i></th>
                                <th scope="col">التاريخ</i></th>
                                {{-- <th scope="col"><i class="bi bi-geo-alt-fill"></i></th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movements as $index => $movement)
                                {{-- saddress
                                    eaddress
                                    slat
                                    along
                                    elat
                                    elong
                                    date
                                    totalPrice
                                    way --}}
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>

                                    <td>{{ $movement->saddress }}</td>
                                    <td>{{ $movement->eaddress }}</td>
                                    <td>
                                        {{ $movement->date }}
                                    </td>
                                    <td>
                                        {{ $movement->totalPrice }}
                                    </td>
                                    <td>
                                        {{ $movement->way }} KM
                                    </td>
                                    <td>
                                        {{ date('Y-m-d', strtotime($movement->date)) }}
                                    </td>
                                    {{-- <td>
                                        @if ($movement->taxi_id)
                                            <a href="{{ route('map', ['selector' => 'completed', 'id' => $movement->movement_id]) }}"
                                                class="btn btn-success">عرض المسار</a>
                                        @else
                                            <!-- Handle the case when taxi_id is missing or null -->
                                            <span class="text-danger">غير متاح</span>
                                        @endif
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
