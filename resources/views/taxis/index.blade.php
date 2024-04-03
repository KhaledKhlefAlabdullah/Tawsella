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
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <table class="table table-borderless datatable">
                        <thead>
                            <tr style="margin-left: 15px">
                                <th>#</th>
                                <th>
                                    <p class="text-center">{{ __('اسم السائق') }}</p>
                                </th>
                                <th>
                                    <p class="text-center">{{ __('اسم السيارة') }}</p>
                                </th>
                                <th>
                                    <p class="text-center">{{ __('رقم المصباح') }}</p>
                                </th>
                                <th>
                                    <p class="text-center">{{ __('رقم اللوحة') }}</p>
                                </th>
                                <th>
                                    <p class="text-center">{{ __('تفاصيل السيارة') }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($taxis as $taxi)
                                <tr>
                                    <td>
                                        <p class="text-center">{{ $loop->iteration }}</p>
                                    </td>
                                    <td>
                                        <p class="text-center">{{ $taxi->driverName }}</p>
                                    </td>
                                    <td>
                                        <p class="text-center">{{ $taxi->car_name }}</p>
                                    </td>
                                    <td>
                                        <p class="text-center">{{ $taxi->lamp_number }}</p>
                                    </td>
                                    <td>
                                        <p class="text-center">{{ $taxi->plate_number }}</p>
                                    </td>
                                    <td class="d-flex align-items-center justify-content-center">
                                        <x-buttons 
                                        :delete-route="route('taxis.destroy', $taxi->id) " 
                                        :edit-route="route('taxis.edit', $taxi->id)" 
                                        :showDeleteButton="true"
                                        :showEditButton="true"
                                        :showDetailsButton="false" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
