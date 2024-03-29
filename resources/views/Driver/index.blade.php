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
                    <table class="table table-borderless datatable">
                        <thead>
                            <tr style="margin-left: 15px">
                                <th scope="col">#</th>
                                <th scope="col">
                                    <p class="text-center">{{ __('السائق') }}</p>
                                </th>
                                <th scope="col">
                                    <p class="text-center">{{ __('الايميل') }}</p>
                                </th>
                                <th scope="col">
                                    <p class="text-center">{{ __('رقم الجوال') }}</p>
                                </th>
                                <th scope="col">
                                    <p class="text-center">{{ __('رقم لوحة السيارة') }}</p>
                                </th>
                                <th scope="col">
                                    <p class="text-center">{{ __('رقم فانوس السيارة') }}</p>
                                </th>
                                <th scope="col">
                                    <p class="text-center">{{ __('حالة الحساب') }}</p>
                                </th>
                                <th scope="col">
                                    <p class="text-center">{{ __('رصيد اليوم') }}</p>
                                </th>
                                <th scope="col">
                                    <p class="text-center">{{ __('اجمالي الرصيد') }}</p>
                                </th>
                                <th scope="col">
                                    <p class="text-center">{{ __('ادارة') }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($drivers as $driver)
                                <tr>
                                    <th scope="row"><a href="#">{{ $loop->iteration }}</a></th>
                                    <td>
                                        <p class="text-center">{{ $driver->name }}</p>
                                    </td>
                                    <td><a href="#" class="text-primary">
                                            <p class="text-center">{{ $driver->email }}</p>
                                        </a></td>
                                    <td>
                                        <p class="text-center">{{ $driver->phoneNumber }}</p>
                                    </td>
                                    <td>
                                        <p class="text-center">{{ $driver->plate_number }}</p>
                                    </td>
                                    <td>
                                        <p class="text-center">{{ $driver->lamp_number }}</p>
                                    </td>
                                    <td>
                                        <span class="badge {{ $driver->is_active ? 'bg-success' : 'bg-danger' }} ">
                                            <center>{{ $driver->is_active ? __('Active') : __('Inactive') }}</center>
                                        </span>
                                    </td>
                                    <td>
                                        <p class="text-center">{{ $driver->total_today}}</p>
                                    </td>
                                    <td>
                                        <p class="text-center">{{ $driver->total_previous}}</p>
                                    </td>
                                    <td>
                                            <div class="btn-group" role="group" aria-label="Driver Actions">
                                                <form action="{{ route('drivers.destroy', ['id' => $driver->driver_id]) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>
                                                </form>
                                        
                                                <span class="pipe">|</span>
                                        
                                                <a href="{{ route('drivers.edit', ['id' => $driver->driver_id]) }}" class="btn btn-primary rounded" title="{{ __('Edit') }}"><i class="fas fa-pencil-alt"></i></a>
                                                
                                                <span class="pipe">|</span>
                                        
                                                <a href="{{ route('drivers.show', ['id' => $driver->driver_id]) }}" class="btn btn-info rounded" title="{{ __('Details') }}"><i class="fas fa-info-circle"></i></a>
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
