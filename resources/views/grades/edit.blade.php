<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa điểm</title>
</head>
<body>
    <h2>Sửa điểm</h2>

    @if($errors->any())
        <ul style="color:red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('grades.update', $grade->id) }}">
        @csrf
        @method('PUT')

        <label>Học sinh:</label>
        <select name="student_id">
            @foreach($students as $student)
                <option value="{{ $student->id }}"
                    {{ $grade->student_id == $student->id ? 'selected' : '' }}>
                    {{ $student->name }}
                </option>
            @endforeach
        </select><br><br>

        <label>Môn học:</label>
        <select name="subject_id">
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}"
                    {{ $grade->subject_id == $subject->id ? 'selected' : '' }}>
                    {{ $subject->name }}
                </option>
            @endforeach
        </select><br><br>

        <label>Điểm (0-10):</label>
        <input type="number" name="score" step="0.1" min="0" max="10"
               value="{{ $grade->score }}"><br><br>

        <label>Học kỳ:</label>
        <select name="semester">
            <option value="HK1-2025" {{ $grade->semester == 'HK1-2025' ? 'selected' : '' }}>HK1 - 2025</option>
            <option value="HK2-2025" {{ $grade->semester == 'HK2-2025' ? 'selected' : '' }}>HK2 - 2025</option>
            <option value="HK1-2026" {{ $grade->semester == 'HK1-2026' ? 'selected' : '' }}>HK1 - 2026</option>
            <option value="HK2-2026" {{ $grade->semester == 'HK2-2026' ? 'selected' : '' }}>HK2 - 2026</option>
        </select><br><br>

        <button type="submit">Cập nhật</button>
        <a href="{{ route('grades.index') }}">Hủy</a>
    </form>
    
</body>
</html>