@extends('layouts.app')

@section('content')
<main class="main" id="main">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Current Taxi Movement</h1>
                <div class="table-responsive"> <!-- تجعل الجدول متجاوبًا -->
                    <table class="table table-striped"> <!-- إضافة فئة table-striped لتظليل الصفوف بشكل بديل -->
                        <thead class="table"> <!-- تحديد لون خلفية العناوين -->
                            <tr>
                                <th>العميل</th>
                                <th>رقم العميل</th>
                                <th>مكان انطلاق العميل</th>
                                <th>وجهة العميل</th>
                                <th>الجنس</th>
                                <th>السائق</th>
                                <th>رقم السائق</th>
                                <th>فانوس السيارة</th>
                                <th>لوحة السيارة</th>
                                <th>نوع الطلب</th>
                                <th>التكلفة</th>
                                <th>تتبع</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($taxiMovement as $movement)
                            <tr>
                                <td>{{ $movement->customer_name }}</td>
                                <td>{{ $movement->customer_phone }}</td>
                                <td>{{ $movement->my_address }}</td>
                                <td>{{ $movement->destnation_address }}</td>
                                <td>{{ $movement->gender }}</td>
                                <td>{{ $movement->driver_name }}</td>
                                <td>{{ $movement->driver_phone }}</td>
                                <td>{{ $movement->car_lamp_number }}</td>
                                <td>{{ $movement->car_plate_number }}</td>
                                <td>{{ $movement->type }}</td>
                                <td>{{ $movement->price }}</td>
                                <td>
                                    @if($movement->taxi_id)
                                        <a href="{{ route('map', [$movement->taxi_id]) }}" class="btn btn-success">موقع السيارة</a>
                                    @else
                                        <!-- Handle the case when taxi_id is missing or null -->
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
