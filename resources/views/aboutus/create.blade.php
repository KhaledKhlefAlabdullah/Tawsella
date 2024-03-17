
<!-- resources/views/aboutus/create.blade.php -->

    <h1>إنشاء نبذة عنا جديدة</h1>

    <form action="{{ route('aboutus.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">العنوان:</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">الوصف:</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="complaints_number">عدد الشكاوى:</label>
            <input type="number" name="complaints_number" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">حفظ</button>
    </form>

