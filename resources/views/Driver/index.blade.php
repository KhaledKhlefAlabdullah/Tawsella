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
                    <table class="table table-borderless datatable">
                        <thead>
                            <tr style="margin-left: 15px">
                                <th scope="col">#</th>
                                <th scope="col">{{ __('السائق') }}</th>
                                <th scope="col">{{ __('الايميل') }}</th>
                                <th scope="col">{{ __('الرصيد') }}</th>
                                <th scope="col">{{ __('رقم السيارة') }}</th>
                                <th scope="col">{{ __('حالة الحساب') }}</th>
                                <th scope="col">{{ __('ادارة') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($drivers as $driver)
                                <tr>
                                    <th scope="row"><a href="#">{{ $loop->iteration }}</a></th>
                                    <td>{{ $driver->name }}</td>
                                    <td><a href="#" class="text-primary">{{ $driver->email }}</a></td>
                                    <td>
                                        {{-- balances --}}
                                        {{-- @if ($driver->padding)
                                            <a href="{{ route('balances.show', $driver->id) }}">$
                                                {{ $driver->balance->credit_balance }}</a>
                                        @else
                                            {{ __('No balance available') }}
                                        @endif --}}
                                    </td>
                                    <td>
                                        <a href="#">{{ $driver->plate_number }}</a>
                                    </td>
                                    <td>
                                        <span class="badge {{ $driver->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $driver->is_active ? __('Active') : __('Inactive') }}
                                        </span>
                                    </td>
                                    <td>
                                        {{-- <form action="{{ route('users.toggleStatus', $driver->id) }}" method="POST"
                                            onsubmit="return confirmStatusChange(event, {{ $driver->is_active }})">
                                            @csrf
                                            @method('POST')
                                            <button type="submit">
                                                <i class="bi bi-power" style="margin-left: 30px;"></i>
                                            </button>
                                        </form> --}}
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
