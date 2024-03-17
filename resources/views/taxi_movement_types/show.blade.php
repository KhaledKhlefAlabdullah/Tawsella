<!-- resources/views/taxi_movement_types/show.blade.php -->


    <h1>تفاصيل نوع الحركة</h1>

    <p><strong>النوع:</strong> {{ $movementType->type }}</p>
    <p><strong>السعر:</strong> {{ $movementType->price }}</p>

    <a href="{{ route('taxi_movement_types.index') }}" class="btn btn-secondary">رجوع</a>
