<!-- resources/views/taxis/show.blade.php -->

    <h1>تفاصيل السيارة</h1>

    <table class="table mt-3">
        <tbody>
            <tr>
                <th scope="row">اسم السائق:</th>
                <td>{{ $taxi->driver->name }}</td>
            </tr>
            <tr>
                <th scope="row">اسم السيارة:</th>
                <td>{{ $taxi->care_name }}</td>
            </tr>
            <tr>
                <th scope="row">رقم اللوحة:</th>
                <td>{{ $taxi->plate_number }}</td>
            </tr>
            <tr>
                <th scope="row">التفاصيل:</th>
                <td>{{ $taxi->car_detailes }}</td>
            </tr>
        </tbody>
    </table>

    <a href="{{ route('taxis.index') }}" class="btn btn-primary">الرجوع إلى القائمة</a>

