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
                    <li><a class="dropdown-item" href="#">{{ __('كل التكاسي') }}</a></li>
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
                <style>
                    .bootstrap-table .fixed-table-toolbar .search input {
                        width: 100%;
                        margin-bottom: 10px; /* تأكد من التباعد */
                        border: 2px solid rgb(255, 149, 0); /* الحدود الحمراء كما في الصورة */
                    }
                    .bootstrap-table .fixed-table-toolbar {
                        margin-bottom: 0; /* لإزالة التباعد الزائد إن وجد */
                    }
                    .bootstrap-table .fixed-table-container {
                        border: none; /* لإزالة الحدود حول الجدول إن وجدت */
                    }
                </style>
                <table class="table table-borderless datatable" data-toggle="table" data-pagination="true"
                       data-search="true" data-search-align="left" data-show-columns="true" data-show-export="true">
                    <thead>
                        <tr>
                            <th data-sortable="true" scope="col">#</th>
                            <th data-sortable="true" scope="col">{{ __('اسم السائق') }}</th>
                            <th data-sortable="true" scope="col">{{ __('اسم السيارة') }}</th>
                            <th data-sortable="true" scope="col">{{ __('رقم المصباح') }}</th>
                            <th data-sortable="true" scope="col">{{ __('رقم اللوحة') }}</th>
                            <th scope="col">{{ __('تفاصيل السيارة') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($taxis as $taxi)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $taxi->driverName ?? 'لا يوجد سائق' }}</td>
                            <td>{{ $taxi->car_name }}</td>
                            <td>{{ $taxi->lamp_number }}</td>
                            <td>{{ $taxi->plate_number }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <x-buttons :delete-route="route('taxis.destroy', $taxi->id)" :edit-route="route('taxis.edit', $taxi->id)" :showDeleteButton="true" :showEditButton="true" :showDetailsButton="false" />
                                </div>
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
