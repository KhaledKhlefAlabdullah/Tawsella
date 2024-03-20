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
                        <li><a class="dropdown-item" href="#">{{ __('كل السائقين') }}</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ __('التكاسي') }} <span>| {{ __('سجل') }}</span></h5>
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <table class="table table-borderless datatable">
                        <thead>
                            <tr style="margin-left: 15px">
                                <th scope="col">#</th>
                                <th scope="col">{{ __('اسم السائق') }}</th>
                                <th scope="col">{{ __('رقم الهاتف') }}</th>
                                <th scope="col">{{ __('البريد الإلكتروني') }}</th>
                                <th scope="col">{{ __('رقم اللوحة') }}</th>
                                <th scope="col">{{ __('تفاصيل السيارة') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($taxis as $taxi)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $taxi->driver_id }}</td>
                                    <td>{{ $taxi->care_name }}</td>
                                    <td>{{ $taxi->lamp_number }}</td>
                                    <td>{{ $taxi->plate_number }}</td>
                                    <td>{{ $taxi->car_detailes }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
