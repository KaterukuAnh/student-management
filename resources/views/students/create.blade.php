<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm học sinh</title>
</head>
<body>
    <h2>Thêm học sinh mới</h2>

    @if($errors->any())
        <ul style="color:red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('students.store') }}">
        @csrf

        <label>Họ tên:</label>
        <input type="text" name="name" value="{{ old('name') }}"><br><br>

        <label>Ngày sinh:</label>
        <input type="date" name="birth_date" value="{{ old('birth_date') }}"><br><br>

        <label>Giới tính:</label>
        <!-- <select name="gender">
            <option value="">-- Chọn --</option>
            <option value="male"   {{ old('gender') == 'male'   ? 'selected' : '' }}>Nam</option>
            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
        </select><br><br> -->

        <select name="gender">
            <option value="">-- Chọn --</option>
            <option value="male"           {{ old('gender') == 'male'           ? 'selected' : '' }}>Nam</option>
            <option value="female"         {{ old('gender') == 'female'         ? 'selected' : '' }}>Nữ</option>
            <option value="non_binary"     {{ old('gender') == 'non_binary'     ? 'selected' : '' }}>Phi nhị nguyên</option>
            <option value="genderfluid"    {{ old('gender') == 'genderfluid'    ? 'selected' : '' }}>Genderfluid</option>
            <option value="agender"        {{ old('gender') == 'agender'        ? 'selected' : '' }}>Agender</option>
            <option value="bigender"       {{ old('gender') == 'bigender'       ? 'selected' : '' }}>Bigender</option>
            <option value="demiboy"        {{ old('gender') == 'demiboy'        ? 'selected' : '' }}>Demiboy</option>
            <option value="demigirl"       {{ old('gender') == 'demigirl'       ? 'selected' : '' }}>Demigirl</option>
            <option value="trans_male"     {{ old('gender') == 'trans_male'     ? 'selected' : '' }}>Chuyển giới nam</option>
            <option value="trans_female"   {{ old('gender') == 'trans_female'   ? 'selected' : '' }}>Chuyển giới nữ</option>
            <option value="genderqueer"    {{ old('gender') == 'genderqueer'    ? 'selected' : '' }}>Genderqueer</option>
            <option value="androgynous"    {{ old('gender') == 'androgynous'    ? 'selected' : '' }}>Androgynous</option>
            <option value="neutrois"       {{ old('gender') == 'neutrois'       ? 'selected' : '' }}>Neutrois</option>
            <option value="two_spirit"     {{ old('gender') == 'two_spirit'     ? 'selected' : '' }}>Two-Spirit</option>
            <option value="xenogender"     {{ old('gender') == 'xenogender'     ? 'selected' : '' }}>Xenogender</option>
            <option value="catgender"      {{ old('gender') == 'catgender'      ? 'selected' : '' }}>Catgender</option>
            <option value="stargender"     {{ old('gender') == 'stargender'     ? 'selected' : '' }}>Stargender</option>
            <option value="cloudgender"    {{ old('gender') == 'cloudgender'    ? 'selected' : '' }}>Cloudgender</option>
            <option value="voidgender"     {{ old('gender') == 'voidgender'     ? 'selected' : '' }}>Voidgender</option>
            <option value="other"          {{ old('gender') == 'other'          ? 'selected' : '' }}>Khác</option>
</select><br><br>

        <label>Email:</label>
        <input type="email" name="email" value="{{ old('email') }}"><br><br>

        <label>Lớp:</label>
        <select name="classroom_id">
            <option value="">-- Chọn lớp --</option>
            @foreach($classrooms as $classroom)
                <option value="{{ $classroom->id }}"
                    {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                    {{ $classroom->name }}
                </option>
            @endforeach
        </select><br><br>

        <button type="submit">Lưu</button>
        <a href="{{ route('students.index') }}">Hủy</a>
    </form>
</body>
</html>