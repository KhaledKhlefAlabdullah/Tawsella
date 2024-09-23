@extends('layouts.app')

@section('content')
    <div class="main" id="main">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-12 mx-auto">
                    <div class="card z-index-0 fadeIn3 fadeInBottom">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                                <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">إضافة سجل تاكسي جديد</h4>
                            </div>
                        </div>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
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

                            <form method="POST" action="{{ route('taxis.store') }}">
                                @csrf

                                <!-- Driver -->
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label" for="driver_id">{{ __('السائق') }}</label>
                                    <select class="form-control" id="driver_id" name="driver_id" required>
                                        <option value="">اختر السائق</option>
                                        @foreach ($drivers as $driver)
                                            <option value="{{ $driver->id }}">{{ $driver->profile->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Car Name -->
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label" for="car_name">{{ __('اسم السيارة') }}</label>
                                    <input type="text" class="form-control" id="car_name" name="car_name" required>
                                </div>

                                <!-- Lamp Number -->
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label" for="lamp_number">{{ __('رقم المصباح') }}</label>
                                    <input type="text" class="form-control" id="lamp_number" name="lamp_number" required>
                                </div>

                                <!-- Plate Number -->
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label" for="plate_number">{{ __('رقم اللوحة') }}</label>
                                    <input type="text" class="form-control" id="plate_number" name="plate_number" required>
                                </div>

                                <!-- Car Details -->
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label" for="car_details">{{ __('تفاصيل السيارة') }}</label>
                                    <textarea class="form-control" id="car_details" name="car_details" required></textarea>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">{{ __('إضافة') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
