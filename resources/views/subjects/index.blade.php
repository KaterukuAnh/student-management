<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách môn học</title>
</head>
<body>
    <h2>Danh sách môn học</h2>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    <a href="{{ route('subjects.create') }}">+ Thêm môn học</a> |
    <a href="{{ route('students.index') }}">Học sinh</a> |
    <a href="{{ route('classrooms.index') }}">Lớp học</a>

    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Tên môn</th>
            <th>Số tiết</th>
            <th>Hành động</th>
        </tr>
        @foreach($subjects as $subject)
        <tr>
            <td>{{ $subject->id }}</td>
            <td>{{ $subject->name }}</td>
            <td>{{ $subject->credits }}</td>
            <td>
                <a href="{{ route('subjects.edit', $subject->id) }}">Sửa</a>
                <form action="{{ route('subjects.destroy', $subject->id) }}"
                      method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Xóa môn này?')">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    
</body>
</html>