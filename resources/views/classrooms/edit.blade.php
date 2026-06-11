<!DOCTYPE html>
<html>
<head>
    <title>Sửa lớp</title>
</head>
<body>
    <h2>Sửa lớp học</h2>

    @if($errors->any())
        <ul style="color:red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('classrooms.update', $classroom->id) }}">
        @csrf
        @method('PUT')

        <label>Tên lớp:</label>
        <input type="text" name="name" value="{{ $classroom->name }}"><br><br>

        <label>Khối:</label>
        <select name="grade">
            <option value="10" {{ $classroom->grade == '10' ? 'selected' : '' }}>Khối 10</option>
            <option value="11" {{ $classroom->grade == '11' ? 'selected' : '' }}>Khối 11</option>
            <option value="12" {{ $classroom->grade == '12' ? 'selected' : '' }}>Khối 12</option>
        </select><br><br>

        <button type="submit">Cập nhật</button>
        <a href="{{ route('classrooms.index') }}">Hủy</a>
    </form>
</body>
</html>