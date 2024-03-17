<!-- resources/views/offers/edit.blade.php -->


    <h1>تحديث العرض</h1>

    <form action="{{ route('offers.update', $offer->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="movement_type_id">نوع الحركة:</label>
            <select name="movement_type_id" class="form-control" required>
                @foreach ($movementTypes as $movementType)
                    <option value="{{ $movementType->id }}" {{ $movementType->id == $offer->movement_type_id ? 'selected' : '' }}>{{ $movementType->type }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="admin_id">المشرف:</label>
            <select name="admin_id" class="form-control" required>
                @foreach ($admins as $admin)
                    <option value="{{ $admin->id }}" {{ $admin->id == $offer->admin_id ? 'selected' : '' }}>{{ $admin->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="offer">العرض:</label>
            <input type="text" name="offer" class="form-control" value="{{ $offer->offer }}" required>
        </div>
        <div class="form-group">
            <label for="value_of_discount">قيمة الخصم:</label>
            <input type="number" name="value_of_discount" class="form-control" value="{{ $offer->value_of_discount }}" required>
        </div>
        <div class="form-group">
            <label for="valide_date">تاريخ الصلاحية:</label>
            <input type="date" name="valide_date" class="form-control" value="{{ $offer->valide_date }}" required>
        </div>
        <button type="submit" class="btn btn-primary">تحديث</button>
    </form>
