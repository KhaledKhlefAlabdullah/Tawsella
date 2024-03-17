<!-- resources/views/taxi_movement_types/create.blade.php -->


<br>
<br>
<br>
<br>
    <h1>إنشاء نوع حركة جديد</h1>

    <form action="{{ route('taxi_movement_types.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="type">النوع:</label>
            <input type="text" name="type" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="price">السعر:</label>
            <input type="number" name="price" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">حفظ</button>
    </form>
