<!-- show.blade.php -->

@extends('layouts.app')

@section('content')
    <main class="main" id="main">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">تفاصيل التاكسي</div>

                        <div class="card-body">
                            <p><strong>معرف السائق:</strong> {{ $taxi->driver_id }}</p>
                            <p><strong>اسم السيارة:</strong> {{ $taxi->car_name }}</p>
                            <p><strong>رقم المصباح:</strong> {{ $taxi->lamp_number }}</p>
                            <p><strong>رقم اللوحة:</strong> {{ $taxi->plate_number }}</p>
                            <p><strong>تفاصيل السيارة:</strong> {{ $taxi->car_detailes }}</p>
                            <!-- زر التعديل -->
                            <a href="{{ route('taxis.edit', $taxi->id) }}" class="btn btn-primary">{{ __('تعديل') }}</a>

                            <!-- زر الحذف -->
                            <form action="{{ route('taxis.destroy', $taxi->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('هل أنت متأكد أنك تريد حذف هذا السجل؟')">{{ __('حذف') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
