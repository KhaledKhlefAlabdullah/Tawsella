<!-- resources/views/offers/create.blade.php -->


    <h1>إنشاء عرض جديد</h1>

    <form action="{{ route('offers.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="movement_type_id">نوع الحركة:</label>
            <select name="movement_type_id" class="form-control" required>
                @foreach ($movementTypes as $movementType)
                    <option value="{{ $movementType->id }}">{{ $movementType->type }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="offer">العرض:</label>
            <input type="text" name="offer" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="value_of_discount">قيمة الخصم:</label>
            <input type="number" name="value_of_discount" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="valide_date">تاريخ الصلاحية:</label>
            <input type="date" name="valide_date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">حفظ</button>
    </form>
