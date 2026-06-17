<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm môn học</title>
</head>
<body>
    <h2>Thêm môn học mới</h2>

    @if($errors->any())
        <ul style="color:red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('subjects.store') }}">
        @csrf

        <label>Tên môn:</label>
        <input type="text" name="name" value="{{ old('name') }}"><br><br>

        <label>Số tiết:</label>
        <input type="number" name="credits" value="{{ old('credits') }}" min="1"><br><br>

        <button type="submit">Lưu</button>
        <a href="{{ route('subjects.index') }}">Hủy</a>
    </form>
    
</body>
</html>