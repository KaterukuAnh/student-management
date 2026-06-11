<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa thông tin học sinh</title>
</head>
<body>
    <h2>Sửa thông tin học sinh</h2>

    @if($errors->any())
        <ul style="color:red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('students.update', $student->id) }}">
        @csrf
        @method('PUT')

        <label>Họ tên:</label>
        <input type="text" name="name" value="{{ $student->name }}"><br><br>

        <label>Ngày sinh:</label>
        <input type="date" name="birth_date" value="{{ $student->birth_date }}"><br><br>

        <!-- <label>Giới tính:</label>
        <select name="gender">
            <option value="male"   {{ $student->gender == 'male'   ? 'selected' : '' }}>Nam</option>
            <option value="female" {{ $student->gender == 'female' ? 'selected' : '' }}>Nữ</option>
        </select><br><br> -->

        <label>Giới tính:</label>
        <select name="gender">
            <option value="male"           {{ $student->gender == 'male'           ? 'selected' : '' }}>Nam</option>
            <option value="female"         {{ $student->gender == 'female'         ? 'selected' : '' }}>Nữ</option>
            <option value="non_binary"     {{ $student->gender == 'non_binary'     ? 'selected' : '' }}>Phi nhị nguyên</option>
            <option value="genderfluid"    {{ $student->gender == 'genderfluid'    ? 'selected' : '' }}>Genderfluid</option>
            <option value="agender"        {{ $student->gender == 'agender'        ? 'selected' : '' }}>Agender</option>
            <option value="bigender"       {{ $student->gender == 'bigender'       ? 'selected' : '' }}>Bigender</option>
            <option value="demiboy"        {{ $student->gender == 'demiboy'        ? 'selected' : '' }}>Demiboy</option>
            <option value="demigirl"       {{ $student->gender == 'demigirl'       ? 'selected' : '' }}>Demigirl</option>
            <option value="trans_male"     {{ $student->gender == 'trans_male'     ? 'selected' : '' }}>Chuyển giới nam</option>
            <option value="trans_female"   {{ $student->gender == 'trans_female'   ? 'selected' : '' }}>Chuyển giới nữ</option>
            <option value="genderqueer"    {{ $student->gender == 'genderqueer'    ? 'selected' : '' }}>Genderqueer</option>
            <option value="androgynous"    {{ $student->gender == 'androgynous'    ? 'selected' : '' }}>Androgynous</option>
            <option value="neutrois"       {{ $student->gender == 'neutrois'       ? 'selected' : '' }}>Neutrois</option>
            <option value="two_spirit"     {{ $student->gender == 'two_spirit'     ? 'selected' : '' }}>Two-Spirit</option>
            <option value="xenogender"     {{ $student->gender == 'xenogender'     ? 'selected' : '' }}>Xenogender</option>
            <option value="catgender"      {{ $student->gender == 'catgender'      ? 'selected' : '' }}>Catgender</option>
            <option value="stargender"     {{ $student->gender == 'stargender'     ? 'selected' : '' }}>Stargender</option>
            <option value="cloudgender"    {{ $student->gender == 'cloudgender'    ? 'selected' : '' }}>Cloudgender</option>
            <option value="voidgender"     {{ $student->gender == 'voidgender'     ? 'selected' : '' }}>Voidgender</option>
            <option value="other"          {{ $student->gender == 'other'          ? 'selected' : '' }}>Khác</option>
        </select><br><br>

        <label>Email:</label>
        <input type="email" name="email" value="{{ $student->email }}"><br><br>

        <label>Lớp:</label>
        <select name="classroom_id">
            @foreach($classrooms as $classroom)
                <option value="{{ $classroom->id }}"
                    {{ $student->classroom_id == $classroom->id ? 'selected' : '' }}>
                    {{ $classroom->name }}
                </option>
            @endforeach
        </select><br><br>

        <button type="submit">Cập nhật</button>
        <a href="{{ route('students.index') }}">Hủy</a>
    </form>
</body>
</html>