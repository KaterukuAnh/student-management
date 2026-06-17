<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách điểm</title>
</head>
<body>
    <h2>Danh sách điểm</h2>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    <a href="{{ route('grades.create') }}">+ Nhập điểm</a> |
    <a href="{{ route('students.index') }}">Học sinh</a> |
    <a href="{{ route('subjects.index') }}">Môn học</a> |
    <a href="{{ route('classrooms.index') }}">Lớp học</a>

    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Học sinh</th>
            <th>Môn học</th>
            <th>Điểm</th>
            <th>Học kỳ</th>
            <th>Hành động</th>
        </tr>
        @foreach($grades as $grade)
        <tr>
            <td>{{ $grade->id }}</td>
            <td>{{ $grade->student->name }}</td>
            <td>{{ $grade->subject->name }}</td>
            <td>{{ $grade->score }}</td>
            <td>{{ $grade->semester }}</td>
            <td>
                <a href="{{ route('grades.edit', $grade->id) }}">Sửa</a>
                <form action="{{ route('grades.destroy', $grade->id) }}"
                      method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Xóa điểm này?')">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    
</body>
</html>