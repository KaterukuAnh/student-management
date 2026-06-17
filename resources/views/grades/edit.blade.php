@extends('layouts.app')

@section('title', 'Sửa điểm')

@section('content')
    <div class="max-w-lg bg-white rounded shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Sửa điểm</h2>

        @if($errors->any())
            <ul class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('grades.update', $grade->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Học sinh</label>
                <select name="student_id"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($students as $student)
                        <option value="{{ $student->id }}"
                            {{ $grade->student_id == $student->id ? 'selected' : '' }}>
                            {{ $student->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Môn học</label>
                <select name="subject_id"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}"
                            {{ $grade->subject_id == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Điểm (0-10)</label>
                <input type="number" name="score" step="0.1" min="0" max="10"
                       value="{{ $grade->score }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Học kỳ</label>
                <select name="semester"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="HK1-2025" {{ $grade->semester == 'HK1-2025' ? 'selected' : '' }}>HK1 - 2025</option>
                    <option value="HK2-2025" {{ $grade->semester == 'HK2-2025' ? 'selected' : '' }}>HK2 - 2025</option>
                    <option value="HK1-2026" {{ $grade->semester == 'HK1-2026' ? 'selected' : '' }}>HK1 - 2026</option>
                    <option value="HK2-2026" {{ $grade->semester == 'HK2-2026' ? 'selected' : '' }}>HK2 - 2026</option>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Cập nhật</button>
                <a href="{{ route('grades.index') }}"
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Hủy</a>
            </div>
        </form>
    </div>
@endsection