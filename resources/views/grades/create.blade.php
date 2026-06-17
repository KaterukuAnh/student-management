<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhập điểm mới</title>
</head>
<body>
    <h2>Nhập điểm mới</h2>

    @if($errors->any())
        <ul style="color:red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('grades.store') }}">
        @csrf

        <label>Học sinh:</label>
        <select name="student_id">
            <option value="">-- Chọn học sinh --</option>
            @foreach($students as $student)
                <option value="{{ $student->id }}"
                    {{ old('student_id') == $student->id ? 'selected' : '' }}>
                    {{ $student->name }}
                </option>
            @endforeach
        </select><br><br>

        <label>Môn học:</label>
        <select name="subject_id">
            <option value="">-- Chọn môn --</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}"
                    {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                    {{ $subject->name }}
                </option>
            @endforeach
        </select><br><br>

        <label>Điểm (0-10):</label>
        <input type="number" name="score" step="0.1" min="0" max="10"
               value="{{ old('score') }}"><br><br>

        <label>Học kỳ:</label>
        <select name="semester">
            <option value="">-- Chọn học kỳ --</option>
            <option value="HK1-2025" {{ old('semester') == 'HK1-2025' ? 'selected' : '' }}>HK1 - 2025</option>
            <option value="HK2-2025" {{ old('semester') == 'HK2-2025' ? 'selected' : '' }}>HK2 - 2025</option>
            <option value="HK1-2026" {{ old('semester') == 'HK1-2026' ? 'selected' : '' }}>HK1 - 2026</option>
            <option value="HK2-2026" {{ old('semester') == 'HK2-2026' ? 'selected' : '' }}>HK2 - 2026</option>
        </select><br><br>

        <button type="submit">Lưu</button>
        <a href="{{ route('grades.index') }}">Hủy</a>
    </form>
</body>
</html>