<!-- resources/views/offers/index.blade.php -->


    <h1>قائمة العروض</h1>

    <a href="{{ route('offers.create') }}" class="btn btn-success">إنشاء عرض جديد</a>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">نوع الحركة</th>
                <th scope="col">المشرف</th>
                <th scope="col">العرض</th>
                <th scope="col">قيمة الخصم</th>
                <th scope="col">تاريخ الصلاحية</th>
                <th scope="col">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($offers as $offer)
                <tr>
                    <th scope="row">{{ $offer->id }}</th>
                    <td>{{ $offer->movement_type_offer->type }}</td>
                    <td>{{ $offer->admin_offer->email }}</td>
                    <td>{{ $offer->offer }}</td>
                    <td>{{ $offer->value_of_discount }}%</td>
                    <td>{{ $offer->valide_date }}</td>
                    <td>
                        <a href="{{ route('offers.show', $offer->id) }}" class="btn btn-info">عرض</a>
                        <a href="{{ route('offers.edit', $offer->id) }}" class="btn btn-warning">تعديل</a>
                        <form action="{{ route('offers.destroy', $offer->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
