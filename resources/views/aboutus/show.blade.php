
<!-- resources/views/aboutus/show.blade.php -->


    <h1>تفاصيل نبذة عنا</h1>

    <p><strong>العنوان:</strong> {{ $aboutUs->title }}</p>
    <p><strong>الوصف:</strong> {{ $aboutUs->description }}</p>
    <p><strong>عدد الشكاوى:</strong> {{ $aboutUs->complaints_number }}</p>
    <p><strong>المشرف:</strong> {{ $aboutUs->admin->id }}</p>

    <a href="{{ route('aboutus.index') }}" class="btn btn-secondary">رجوع</a>
