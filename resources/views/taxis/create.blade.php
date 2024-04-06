@extends('layouts.app')

@section('content')
    <main class="main" id="main">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('إضافة سجل تاكسي جديد') }}</div>
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
                        <div class="card-body">
                            <form method="POST" action="{{ route('taxis.store') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="driver_id" class="form-label">{{ __('السائق') }}</label>
                                    <select class="form-select" id="driver_id" name="driver_id" required>
                                        <option value="">اختر السائق</option>
                                        @foreach ($drivers as $driver)
                                            <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="car_name" class="form-label">{{ __('اسم السيارة') }}</label>
                                    <input type="text" class="form-control" id="car_name" name="car_name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="lamp_number" class="form-label">{{ __('رقم المصباح') }}</label>
                                    <input type="text" class="form-control" id="lamp_number" name="lamp_number" required>
                                </div>

                                <div class="mb-3">
                                    <label for="plate_number" class="form-label">{{ __('رقم اللوحة') }}</label>
                                    <input type="text" class="form-control" id="plate_number" name="plate_number"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="car_detailes" class="form-label">{{ __('تفاصيل السيارة') }}</label>
                                    <textarea class="form-control" id="car_detailes" name="car_detailes" required></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">{{ __('إضافة') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
