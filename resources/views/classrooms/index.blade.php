<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách lớp</title>
</head>
<body>
    <h2>Danh sách lớp học</h2>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    <a href="{{ route('classrooms.create') }}">+ Thêm lớp mới</a>

    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Tên lớp</th>
            <th>Khối</th>
            <th>Thao tác</th>
        </tr>
        @foreach($classrooms as $classroom)
        <tr>
            <td>{{ $classroom->id }}</td>
            <td>{{ $classroom->name }}</td>
            <td>{{ $classroom->grade }}</td>
            <td>
                <a href="{{ route('classrooms.edit', $classroom->id) }}">Sửa</a>
                <form action="{{ route('classrooms.destroy', $classroom->id) }}"
                      method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Xóa lớp này?')">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>