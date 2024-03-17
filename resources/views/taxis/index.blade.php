<!-- resources/views/taxis/index.blade.php -->

    <h1>قائمة السيارات</h1>

    <a href="{{ route('taxis.create') }}" class="btn btn-success">إضافة سيارة جديدة</a>

    <table class="table mt-3">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">اسم السائق</th>
                <th scope="col">اسم السيارة</th>
                <th scope="col">رقم اللوحة</th>
                <th scope="col">التفاصيل</th>
                <th scope="col">خيارات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($taxis as $taxi)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $taxi->driver->name }}</td>
                    <td>{{ $taxi->care_name }}</td>
                    <td>{{ $taxi->plate_number }}</td>
                    <td>{{ $taxi->car_detailes }}</td>
                    <td>
                        <a href="{{ route('taxis.show', $taxi->id) }}" class="btn btn-primary">عرض</a>
                        <a href="{{ route('taxis.edit', $taxi->id) }}" class="btn btn-warning">تعديل</a>
                        <form action="{{ route('taxis.destroy', $taxi->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
