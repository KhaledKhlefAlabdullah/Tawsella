<!-- resources/views/taxi_movement_types/index.blade.php -->


    <h1>قائمة أنواع الحركة</h1>

    <a href="{{ route('taxi_movement_types.create') }}" class="btn btn-success">إنشاء نوع حركة جديد</a>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">النوع</th>
                <th scope="col">السعر</th>
                <th scope="col">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($movementTypes as $movementType)
                <tr>
                    <th scope="row">{{ $movementType->id }}</th>
                    <td>{{ $movementType->type }}</td>
                    <td>{{ $movementType->price }}</td>
                    <td>
                        <a href="{{ route('taxi_movement_types.show', $movementType->id) }}" class="btn btn-info">عرض</a>
                        <a href="{{ route('taxi_movement_types.edit', $movementType->id) }}" class="btn btn-warning">تعديل</a>
                        <form action="{{ route('taxi_movement_types.destroy', $movementType->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
