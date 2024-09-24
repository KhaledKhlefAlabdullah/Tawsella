@extends('layouts.app')

@section('content')
<main id="main" class="main">
    <div class="col-12">
        <div class="card recent-sales overflow-auto">
            <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                        <h6>{{ __('فلترة') }}</h6>
                    </li>
                    <li><a class="dropdown-item" href="#">{{ __('كل الطلبات') }}</a></li>
                </ul>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ __('الطلبات الحالية') }} <span>| {{ __('سجل') }}</span></h5>

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

                <style>
                    .bootstrap-table .fixed-table-toolbar .search input {
                        width: 100%;
                        margin-bottom: 10px;
                        border: 2px solid rgb(255, 149, 0);
                    }
                    .bootstrap-table .fixed-table-toolbar {
                        margin-bottom: 0;
                    }
                    .bootstrap-table .fixed-table-container {
                        border: none;
                    }
                </style>

                <div class="table-responsive">
                    <table class="table table-borderless datatable" data-toggle="table" data-pagination="true"
                        data-search="true" data-search-align="left">
                        <thead >
                            <tr>
                                <th data-sortable="true">{{ __('العميل') }}</th>
                                <th data-sortable="true">{{ __('رقم العميل') }}</th>
                                <th data-sortable="true">{{ __('مكان انطلاق العميل') }}</th>
                                <th data-sortable="true">{{ __('وجهة العميل') }}</th>
                                <th data-sortable="true">{{ __('الجنس') }}</th>
                                <th data-sortable="true">{{ __('السائق') }}</th>
                                <th data-sortable="true">{{ __('رقم السائق') }}</th>
                                <th data-sortable="true">{{ __('فانوس السيارة') }}</th>
                                <th data-sortable="true">{{ __('لوحة السيارة') }}</th>
                                <th data-sortable="true">{{ __('نوع الطلب') }}</th>
                                <th>{{ __('تتبع') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($taxiMovement as $movement)
                            <tr>
                                <td>{{ $movement->customer_name }}</td>
                                <td>{{ $movement->customer_phone }}</td>
                                <td>{{ $movement->start_address }}</td>
                                <td>{{ $movement->destination_address }}</td>
                                <td>{{ $movement->gender == 'male' ? 'ذكر' : 'انثى' }}</td>
                                <td>{{ $movement->driver_name }}</td>
                                <td>{{ $movement->driver_phone }}</td>
                                <td>{{ $movement->car_lamp_number }}</td>
                                <td>{{ $movement->car_plate_number }}</td>
                                <td>{{ $movement->type }}</td>
                                <td>
                                    @if ($movement->taxi_id)
                                    <a href="{{ route('movement.life.map', ['taxi' => $movement->taxi_id]) }}"
                                        class="btn btn-success btn-sm">{{ __('موقع السيارة') }}</a>
                                    @else
                                    <span class="text-danger">{{ __('No taxi ID available') }}</span>
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
