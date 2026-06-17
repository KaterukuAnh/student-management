@extends('layouts.app')

@section('title', 'Danh sách môn học')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Danh sách môn học</h2>
        <a href="{{ route('subjects.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Thêm môn học
        </a>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Tên môn</th>
                    <th class="px-6 py-3">Số tiết</th>
                    <th class="px-6 py-3">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($subjects as $subject)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $subject->id }}</td>
                    <td class="px-6 py-4 font-medium">{{ $subject->name }}</td>
                    <td class="px-6 py-4">{{ $subject->credits }}</td>
                    <td class="px-6 py-4 flex gap-3">
                        <a href="{{ route('subjects.edit', $subject->id) }}"
                           class="text-blue-600 hover:underline">Sửa</a>
                        <form action="{{ route('subjects.destroy', $subject->id) }}"
                              method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Xóa môn này?')"
                                    class="text-red-600 hover:underline">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection