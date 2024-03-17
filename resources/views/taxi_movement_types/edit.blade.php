<!-- resources/views/taxi_movement_types/edit.blade.php -->


    <h1>تحديث نوع الحركة</h1>

    <form action="{{ route('taxi_movement_types.update', $movementType->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="type">النوع:</label>
            <input type="text" name="type" class="form-control" value="{{ $movementType->type }}" required>
        </div>
        <div class="form-group">
            <label for="price">السعر:</label>
            <input type="number" name="price" class="form-control" value="{{ $movementType->price }}" required>
        </div>
        <button type="submit" class="btn btn-primary">تحديث</button>
    </form>
