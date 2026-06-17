@extends('layouts.app')

@section('title', 'Danh sách điểm')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Danh sách điểm</h2>
        <a href="{{ route('grades.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Nhập điểm
        </a>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Học sinh</th>
                    <th class="px-6 py-3">Môn học</th>
                    <th class="px-6 py-3">Điểm</th>
                    <th class="px-6 py-3">Học kỳ</th>
                    <th class="px-6 py-3">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($grades as $grade)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $grade->id }}</td>
                    <td class="px-6 py-4 font-medium">{{ $grade->student->name }}</td>
                    <td class="px-6 py-4">{{ $grade->subject->name }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-sm font-semibold
                            {{ $grade->score >= 8 ? 'bg-green-100 text-green-700' :
                               ($grade->score >= 5 ? 'bg-yellow-100 text-yellow-700' :
                               'bg-red-100 text-red-700') }}">
                            {{ $grade->score }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $grade->semester }}</td>
                    <td class="px-6 py-4 flex gap-3">
                        <a href="{{ route('grades.edit', $grade->id) }}"
                           class="text-blue-600 hover:underline">Sửa</a>
                        <form action="{{ route('grades.destroy', $grade->id) }}"
                              method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Xóa điểm này?')"
                                    class="text-red-600 hover:underline">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection