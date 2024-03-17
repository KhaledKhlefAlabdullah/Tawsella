<!-- resources/views/taxis/edit.blade.php -->

    <h1>تعديل بيانات السيارة</h1>

    <form action="{{ route('taxis.update', $taxi->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="driver_id">اسم السائق:</label>
            <select name="driver_id" class="form-control" required>
                @foreach ($drivers as $driver)
                    @if($driver->user_type == 'Driver' && (!$driver->taxi || $driver->taxi->id == $taxi->id))
                        <option value="{{ $driver->id }}" {{ $driver->id == $taxi->driver_id ? 'selected' : '' }}>{{ $driver->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="care_name">اسم السيارة:</label>
            <input type="text" name="care_name" class="form-control" value="{{ $taxi->care_name }}" required>
        </div>

        <div class="form-group">
            <label for="lamp_number">رقم اللوحة:</label>
            <input type="text" name="lamp_number" class="form-control" value="{{ $taxi->lamp_number }}" required>
        </div>

        <div class="form-group">
            <label for="plate_number">التفاصيل:</label>
            <textarea name="car_detailes" class="form-control" required>{{ $taxi->car_detailes }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">حفظ التعديلات</button>
    </form>

