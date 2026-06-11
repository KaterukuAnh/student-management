<!DOCTYPE html>
<html>
<head>
    <title>Thêm lớp</title>
</head>
<body>
    <h2>Thêm lớp mới</h2>

    @if($errors->any())
        <ul style="color:red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('classrooms.store') }}">
        @csrf
        <label>Tên lớp:</label>
        <input type="text" name="name" value="{{ old('name') }}"><br><br>

        <label>Khối:</label>
        <select name="grade">
            <option value="">-- Chọn khối --</option>
            <option value="10">Khối 10</option>
            <option value="11">Khối 11</option>
            <option value="12">Khối 12</option>
        </select><br><br>

        <button type="submit">Lưu</button>
        <a href="{{ route('classrooms.index') }}">Hủy</a>
    </form>
</body>
</html>