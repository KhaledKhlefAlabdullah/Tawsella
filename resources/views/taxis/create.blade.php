<!-- resources/views/taxis/create.blade.php -->


    <h1>إنشاء سيارة جديدة</h1>

    <form action="{{ route('taxis.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="driver_id">اسم السائق:</label>
            <select name="driver_id" class="form-control" required>
                @foreach ($drivers as $driver)
                    @if($driver->user_type == 'Driver' && !$driver->taxi)
                        <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="care_name">اسم السيارة:</label>
            <input type="text" name="care_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="lamp_number">رقم اللوحة:</label>
            <input type="text" name="lamp_number" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="plate_number">التفاصيل:</label>
            <textarea name="car_detailes" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-success">حفظ</button>
    </form>
