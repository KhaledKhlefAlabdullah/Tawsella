<!-- edit.blade.php -->

@extends('layouts.app')

@section('content')

    <main class="main" id="main">
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
        <div class="container">
            <div class="row">
                <div class="col-md-8 center">
                    <div class="card">
                        <div class="card-header">تحرير تفاصيل التاكسي</div>

                        <div class="card-body">
                            <form action="{{ route('taxis.update', $taxi->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <!-- اسم السائق -->
                                <label for="driver_id" class="form-label">السائق</label>
                                <select class="form-select" id="driver_id" name="driver_id" required>
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}"
                                            {{ $taxi->driver_id == $driver->id ? 'selected' : '' }}>
                                            {{ $driver->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <!-- اسم السيارة -->
                                <div class="mb-3">
                                    <label for="car_name" class="form-label">اسم السيارة</label>
                                    <input type="text" class="form-control" id="car_name" name="car_name"
                                        value="{{ $taxi->car_name }}" required>
                                </div>

                                <!-- رقم المصباح -->
                                <div class="mb-3">
                                    <label for="lamp_number" class="form-label">رقم المصباح</label>
                                    <input type="text" class="form-control" id="lamp_number" name="lamp_number"
                                        value="{{ $taxi->lamp_number }}" required>
                                </div>

                                <!-- رقم اللوحة -->
                                <div class="mb-3">
                                    <label for="plate_number" class="form-label">رقم اللوحة</label>
                                    <input type="text" class="form-control" id="plate_number" name="plate_number"
                                        value="{{ $taxi->plate_number }}" required>
                                </div>

                                <!-- تفاصيل السيارة -->
                                <div class="mb-3">
                                    <label for="car_details" class="form-label">تفاصيل السيارة</label>
                                    <textarea class="form-control" id="car_details" name="car_details" rows="3">{{ $taxi->car_details }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
