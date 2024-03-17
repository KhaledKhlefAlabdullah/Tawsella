
<!-- resources/views/aboutus/edit.blade.php -->


    <h1>تحديث نبذة عنا</h1>

    <form action="{{ route('aboutus.update', $aboutUs->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">العنوان:</label>
            <input type="text" name="title" class="form-control" value="{{ $aboutUs->title }}" required>
        </div>
        <div class="form-group">
            <label for="description">الوصف:</label>
            <textarea name="description" class="form-control" required>{{ $aboutUs->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="complaints_number">عدد الشكاوى:</label>
            <input type="number" name="complaints_number" class="form-control" value="{{ $aboutUs->complaints_number }}" required>
        </div>
        <button type="submit" class="btn btn-primary">تحديث</button>
    </form>

