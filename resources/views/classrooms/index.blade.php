@extends('layouts.app')

@section('title', 'Danh sách lớp học')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Danh sách lớp học</h2>
        <a href="{{ route('classrooms.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Thêm lớp mới
        </a>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Tên lớp</th>
                    <th class="px-6 py-3">Khối</th>
                    <th class="px-6 py-3">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($classrooms as $classroom)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $classroom->id }}</td>
                    <td class="px-6 py-4 font-medium">{{ $classroom->name }}</td>
                    <td class="px-6 py-4">Khối {{ $classroom->grade }}</td>
                    <td class="px-6 py-4 flex gap-3">
                        <a href="{{ route('classrooms.edit', $classroom->id) }}"
                           class="text-blue-600 hover:underline">Sửa</a>
                        <form action="{{ route('classrooms.destroy', $classroom->id) }}"
                              method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Xóa lớp này?')"
                                    class="text-red-600 hover:underline">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection