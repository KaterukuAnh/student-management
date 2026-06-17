<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách học sinh</title>
</head>
<body>
    <h2>Danh sách học sinh</h2>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    <a href="{{ route('students.create') }}">+ Thêm học sinh</a> |
    <a href="{{ route('classrooms.index') }}">Quản lý lớp</a>

    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Ngày sinh</th>
            <th>Giới tính</th>
            <th>Email</th>
            <th>Lớp</th>
            <th>Thao tác</th>
        </tr>
        @foreach($students as $student)
        <tr>
            <td>{{ $student->id }}</td>
            <td>{{ $student->name }}</td>
            <td>{{ $student->birth_date }}</td>
            <td>{{ $student->gender == 'male' ? 'Nam' : 'Nữ' }}</td>
            <td>{{ $student->email }}</td>
            <td>{{ $student->classroom->name }}</td>
            <td>
                <a href="{{ route('students.edit', $student->id) }}">Sửa</a>
                <form action="{{ route('students.destroy', $student->id) }}"
                      method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Xóa học sinh này?')">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>