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
                <h5 class="card-title">{{ __('السائقين') }} <span>| {{ __('سجل') }}</span></h5>
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
                <table class="table table-borderless datatable" data-toggle="table" data-pagination="true"
                       data-search="true" data-search-align="left">
                    <thead>
                        <tr style="margin-left: 15px">
                            <th data-sortable="true" scope="col">#</th>
                            <th data-sortable="true" scope="col">{{ __('السائق') }}</th>
                            <th data-sortable="true" scope="col">{{ __('الايميل') }}</th>
                            <th data-sortable="true" scope="col">{{ __('رقم الجوال') }}</th>
                            <th data-sortable="true" scope="col">{{ __('رقم لوحة السيارة') }}</th>
                            <th data-sortable="true" scope="col">{{ __('رقم فانوس السيارة') }}</th>
                            <th data-sortable="true" scope="col">{{ __('حالة الحساب') }}</th>
                            <th data-sortable="true" scope="col">{{ __('حالة السائق') }}</th>
                            <th data-sortable="true" scope="col">{{ __('المبلغ غير المسلم') }}</th>
                            <th scope="col">{{ __('ادارة') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($drivers as $driver)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $driver->name }}</td>
                            <td>{{ $driver->email }}</td>
                            <td>{{ $driver->phone_number }}</td>
                            <td>{{ $driver->plate_number }}</td>
                            <td>{{ $driver->lamp_number }}</td>
                            <td class="text-center">
                                <span class="badge {{ $driver->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $driver->is_active ? __('Active') : __('Inactive') }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($driver->driver_state == 'ready')
                                <span class="badge bg-success">{{ __('مستعد للعمل') }}</span>
                                @elseif ($driver->driver_state == 'in_break')
                                <span class="badge bg-primary">{{ __('في استراحة') }}</span>
                                @else
                                <span class="badge bg-danger">{{ __('مشغول') }}</span>
                                @endif
                            </td>
                            <td>{{ $driver->unBring }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    @if ($driver->unBring > 0)
                                    <a href="{{ route('calculations.bring', ['driver' => $driver->driver_id]) }}"
                                        class="btn btn-sm btn-primary">
                                        {{ __('استلام') }}
                                    </a>
                                    @endif
                                    <x-buttons :delete-route="route('drivers.destroy', ['driver' => $driver->driver_id])" :edit-route="route('drivers.edit', ['driver' => $driver->driver_id])" :show-route="route('drivers.show', ['driver' => $driver->driver_id])" :showDeleteButton="true"
                                        :showEditButton="true" :showDetailsButton="true" />
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
