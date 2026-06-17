@extends('layouts.app')

@section('title', 'Danh sách học sinh')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Danh sách học sinh</h2>
        <a href="{{ route('students.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Thêm học sinh
        </a>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Họ tên</th>
                    <th class="px-6 py-3">Ngày sinh</th>
                    <th class="px-6 py-3">Giới tính</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Lớp</th>
                    <th class="px-6 py-3">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($students as $student)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $student->id }}</td>
                    <td class="px-6 py-4 font-medium">{{ $student->name }}</td>
                    <td class="px-6 py-4">{{ $student->birth_date }}</td>
                    <td class="px-6 py-4">{{ $student->gender }}</td>
                    <td class="px-6 py-4">{{ $student->email }}</td>
                    <td class="px-6 py-4">{{ $student->classroom->name }}</td>
                    <td class="px-6 py-4 flex gap-3">
                        <a href="{{ route('students.edit', $student->id) }}"
                           class="text-blue-600 hover:underline">Sửa</a>
                        <form action="{{ route('students.destroy', $student->id) }}"
                              method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Xóa học sinh này?')"
                                    class="text-red-600 hover:underline">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection