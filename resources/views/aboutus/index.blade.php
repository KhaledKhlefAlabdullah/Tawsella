
<!-- resources/views/aboutus/index.blade.php -->

    <h1>قائمة نبذة عنا</h1>

    <a href="{{ route('aboutus.create') }}" class="btn btn-success">إنشاء نبذة عنا جديدة</a>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">العنوان</th>
                <th scope="col">الوصف</th>
                <th scope="col">عدد الشكاوى</th>
                <th scope="col">المشرف</th>
                <th scope="col">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($aboutUsRecords as $aboutUs)
                <tr>
                    <th scope="row">{{ $aboutUs->id }}</th>
                    <td>{{ $aboutUs->title }}</td>
                    <td>{{ $aboutUs->description }}</td>
                    <td>{{ $aboutUs->complaints_number }}</td>
                    <td>{{ $aboutUs->admin->name }}</td>
                    <td>
                        <a href="{{ route('aboutus.show', $aboutUs->id) }}" class="btn btn-info">عرض</a>
                        <a href="{{ route('aboutus.edit', $aboutUs->id) }}" class="btn btn-warning">تعديل</a>
                        <form action="{{ route('aboutus.destroy', $aboutUs->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
