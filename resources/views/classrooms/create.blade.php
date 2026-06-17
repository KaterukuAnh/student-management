@extends('layouts.app')

@section('title', 'Thêm lớp học')

@section('content')
    <div class="max-w-lg bg-white rounded shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Thêm lớp mới</h2>

        @if($errors->any())
            <ul class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('classrooms.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tên lớp</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Khối</label>
                <select name="grade"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Chọn khối --</option>
                    <option value="10" {{ old('grade') == '10' ? 'selected' : '' }}>Khối 10</option>
                    <option value="11" {{ old('grade') == '11' ? 'selected' : '' }}>Khối 11</option>
                    <option value="12" {{ old('grade') == '12' ? 'selected' : '' }}>Khối 12</option>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Lưu</button>
                <a href="{{ route('classrooms.index') }}"
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Hủy</a>
            </div>
        </form>
    </div>
@endsection