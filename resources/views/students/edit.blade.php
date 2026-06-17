@extends('layouts.app')

@section('title', 'Sửa học sinh')

@section('content')
    <div class="max-w-lg bg-white rounded shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Sửa thông tin học sinh</h2>

        @if($errors->any())
            <ul class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('students.update', $student->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Họ tên</label>
                <input type="text" name="name" value="{{ $student->name }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ngày sinh</label>
                <input type="date" name="birth_date" value="{{ $student->birth_date }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Giới tính</label>
                <select name="gender"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="male"         {{ $student->gender == 'male'         ? 'selected' : '' }}>Nam</option>
                    <option value="female"       {{ $student->gender == 'female'       ? 'selected' : '' }}>Nữ</option>
                    <option value="non_binary"   {{ $student->gender == 'non_binary'   ? 'selected' : '' }}>Phi nhị nguyên</option>
                    <option value="genderfluid"  {{ $student->gender == 'genderfluid'  ? 'selected' : '' }}>Genderfluid</option>
                    <option value="agender"      {{ $student->gender == 'agender'      ? 'selected' : '' }}>Agender</option>
                    <option value="bigender"     {{ $student->gender == 'bigender'     ? 'selected' : '' }}>Bigender</option>
                    <option value="demiboy"      {{ $student->gender == 'demiboy'      ? 'selected' : '' }}>Demiboy</option>
                    <option value="demigirl"     {{ $student->gender == 'demigirl'     ? 'selected' : '' }}>Demigirl</option>
                    <option value="trans_male"   {{ $student->gender == 'trans_male'   ? 'selected' : '' }}>Chuyển giới nam</option>
                    <option value="trans_female" {{ $student->gender == 'trans_female' ? 'selected' : '' }}>Chuyển giới nữ</option>
                    <option value="genderqueer"  {{ $student->gender == 'genderqueer'  ? 'selected' : '' }}>Genderqueer</option>
                    <option value="androgynous"  {{ $student->gender == 'androgynous'  ? 'selected' : '' }}>Androgynous</option>
                    <option value="neutrois"     {{ $student->gender == 'neutrois'     ? 'selected' : '' }}>Neutrois</option>
                    <option value="two_spirit"   {{ $student->gender == 'two_spirit'   ? 'selected' : '' }}>Two-Spirit</option>
                    <option value="xenogender"   {{ $student->gender == 'xenogender'   ? 'selected' : '' }}>Xenogender</option>
                    <option value="catgender"    {{ $student->gender == 'catgender'    ? 'selected' : '' }}>Catgender</option>
                    <option value="stargender"   {{ $student->gender == 'stargender'   ? 'selected' : '' }}>Stargender</option>
                    <option value="cloudgender"  {{ $student->gender == 'cloudgender'  ? 'selected' : '' }}>Cloudgender</option>
                    <option value="voidgender"   {{ $student->gender == 'voidgender'   ? 'selected' : '' }}>Voidgender</option>
                    <option value="other"        {{ $student->gender == 'other'        ? 'selected' : '' }}>Khác</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ $student->email }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lớp</label>
                <select name="classroom_id"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}"
                            {{ $student->classroom_id == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Cập nhật</button>
                <a href="{{ route('students.index') }}"
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Hủy</a>
            </div>
        </form>
    </div>
@endsection