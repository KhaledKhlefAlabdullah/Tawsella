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
            <div class="row">
                <div class="col-md-12">
                    <h1>الطلبات الحالية</h1>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark"> <!-- Bootstrap dark theme for table header -->
                                <tr>
                                    <th scope="col">العميل</th>
                                    <th scope="col">رقم العميل</th>
                                    <th scope="col">مكان انطلاق العميل</th>
                                    <th scope="col">وجهة العميل</th>
                                    <th scope="col">الجنس</th>
                                    <th scope="col">السائق</th>
                                    <th scope="col">رقم السائق</th>
                                    <th scope="col">فانوس السيارة</th>
                                    <th scope="col">لوحة السيارة</th>
                                    <th scope="col">نوع الطلب</th>
                                    <th scope="col">تتبع</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($taxiMovement as $movement)
                                    <tr>
                                        <td>{{ $movement->customer_name }}</td>
                                        <td>{{ $movement->customer_phone }}</td>
                                        <td>{{ $movement->my_address }}</td>
                                        <td>{{ $movement->destnation_address }}</td>
                                        <td>{{ $movement->gender == 'male' ? 'ذكر' : 'انثى' }}</td>
                                        <td>{{ $movement->driver_name }}</td>
                                        <td>{{ $movement->driver_phone }}</td>
                                        <td>{{ $movement->car_lamp_number }}</td>
                                        <td>{{ $movement->car_plate_number }}</td>
                                        <td>{{ $movement->type }}</td>
                                        <td>
                                            @if ($movement->taxi_id)
                                                <a href="{{ route('map', ['selector' => 'taxi', 'id' => $movement->taxi_id]) }}"
                                                    class="btn btn-success btn-sm">موقع السيارة</a> <!-- Bootstrap small button -->
                                            @else
                                                <span class="text-danger">No taxi ID available</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
