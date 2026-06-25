@extends('layouts.app')

@section('title', __('Danh sách điểm'))

@php
    $reopenCreate = $errors->any() && old('_modal') === 'create';
    $reopenEdit = $errors->any() && old('_modal') === 'edit';
@endphp

@section('content')
    <div
        x-data="{ showCreate: {{ $reopenCreate ? 'true' : 'false' }}, editing: null }"
        x-init="@if ($reopenEdit) editing = @js(['id' => old('id'), 'student_id' => old('student_id'), 'subject_id' => old('subject_id'), 'oral_score' => old('oral_score'), 'quiz15_score' => old('quiz15_score'), 'test45_score' => old('test45_score'), 'final_score' => old('final_score'), 'semester' => old('semester')]) @endif"
    >
        <x-page-head crumb="{{ __('Giảng dạy').' · '.__('Điểm số') }}" title="{{ __('Danh sách điểm') }}">
            <x-slot:actions>
                <button type="button" class="btn btn-primary" @click="showCreate = true"><span class="plus">+</span> {{ __('Nhập điểm') }}</button>
            </x-slot:actions>
        </x-page-head>

        <div class="panel">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('Học sinh') }}</th>
                        <th>{{ __('Môn học') }}</th>
                        <th>{{ __('Điểm') }}</th>
                        <th>{{ __('Học kỳ') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($grades as $grade)
                        <tr>
                            <td class="id">{{ $grade->id }}</td>
                            <td class="cell-name"><span class="nm">{{ $grade->student->name }}</span></td>
                            <td>{{ $grade->subject->name }}</td>
                            <td>
                                @if ($grade->score !== null)
                                    <span class="badge {{ $grade->score >= 8 ? 'badge-good' : ($grade->score >= 5 ? 'badge-fair' : 'badge-avg') }}">
                                        {{ number_format($grade->score, 1) }}
                                    </span>
                                @else
                                    <span class="badge badge-soft">—</span>
                                @endif
                            </td>
                            <td>{{ $grade->semester }}</td>
                            <td class="rowact">
                                <button type="button" class="iconbtn" @click="editing = @js(['id' => $grade->id, 'student_id' => $grade->student_id, 'subject_id' => $grade->subject_id, 'oral_score' => $grade->oral_score, 'quiz15_score' => $grade->quiz15_score, 'test45_score' => $grade->test45_score, 'final_score' => $grade->final_score, 'semester' => $grade->semester])">{{ __('Sửa') }}</button>
                                <form action="{{ route('grades.destroy', $grade->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('{{ __('Xóa điểm này?') }}')" class="iconbtn danger">{{ __('Xóa') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty"><div class="big">{{ __('Chưa có điểm nào') }}</div></div></td></tr>
                    @endforelse
                </tbody>
            </table>

            <x-pagination :paginator="$grades" />
        </div>

        <div class="overlay" x-show="showCreate" x-cloak @keydown.escape.window="showCreate = false" style="display:none">
            <div class="modal" @click.away="showCreate = false">
                <div class="modal-h">
                    <span class="ti">{{ __('Nhập điểm mới') }}</span>
                    <button type="button" class="x" @click="showCreate = false">✕</button>
                </div>

                <form method="POST" action="{{ route('grades.store') }}">
                    @csrf
                    <input type="hidden" name="_modal" value="create">

                    <div class="modal-b">
                        @if ($reopenCreate)
                            <div class="login-error">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <div class="form-grid">
                            <div class="field full">
                                <label>{{ __('Học sinh') }}</label>
                                <select name="student_id">
                                    <option value="">-- {{ __('Chọn học sinh') }} --</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field full">
                                <label>{{ __('Môn học') }}</label>
                                <select name="subject_id">
                                    <option value="">-- {{ __('Chọn môn') }} --</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field">
                                <label>{{ __('Học kỳ') }}</label>
                                <select name="semester">
                                    <option value="">-- {{ __('Chọn học kỳ') }} --</option>
                                    <option value="HK1-2025" {{ old('semester') == 'HK1-2025' ? 'selected' : '' }}>HK1 - 2025</option>
                                    <option value="HK2-2025" {{ old('semester') == 'HK2-2025' ? 'selected' : '' }}>HK2 - 2025</option>
                                    <option value="HK1-2026" {{ old('semester') == 'HK1-2026' ? 'selected' : '' }}>HK1 - 2026</option>
                                    <option value="HK2-2026" {{ old('semester') == 'HK2-2026' ? 'selected' : '' }}>HK2 - 2026</option>
                                </select>
                            </div>

                            <div class="field">
                                <label>{{ __('Miệng') }} (0-10)</label>
                                <input type="number" name="oral_score" step="0.1" min="0" max="10" value="{{ old('oral_score') }}" class="inp">
                            </div>

                            <div class="field">
                                <label>{{ __('15 phút') }} (0-10)</label>
                                <input type="number" name="quiz15_score" step="0.1" min="0" max="10" value="{{ old('quiz15_score') }}" class="inp">
                            </div>

                            <div class="field">
                                <label>{{ __('45 phút') }} (0-10)</label>
                                <input type="number" name="test45_score" step="0.1" min="0" max="10" value="{{ old('test45_score') }}" class="inp">
                            </div>

                            <div class="field">
                                <label>{{ __('Cuối kỳ') }} (0-10)</label>
                                <input type="number" name="final_score" step="0.1" min="0" max="10" value="{{ old('final_score') }}" class="inp">
                            </div>
                        </div>
                    </div>

                    <div class="modal-f">
                        <button type="button" class="btn btn-ghost" @click="showCreate = false">{{ __('Hủy') }}</button>
                        <button type="submit" class="btn btn-accent">{{ __('Lưu') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="overlay" x-show="editing" x-cloak @keydown.escape.window="editing = null" style="display:none">
            <div class="modal" @click.away="editing = null" x-show="editing">
                <div class="modal-h">
                    <span class="ti">{{ __('Sửa điểm') }}</span>
                    <button type="button" class="x" @click="editing = null">✕</button>
                </div>

                <template x-if="editing">
                    <form method="POST" :action="'{{ route('grades.index') }}/' + editing.id">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_modal" value="edit">
                        <input type="hidden" name="id" :value="editing.id">

                        <div class="modal-b">
                            @if ($reopenEdit)
                                <div class="login-error">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="form-grid">
                                <div class="field full">
                                    <label>{{ __('Học sinh') }}</label>
                                    <select name="student_id" x-model="editing.student_id">
                                        @foreach ($students as $student)
                                            <option value="{{ $student->id }}">{{ $student->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="field full">
                                    <label>{{ __('Môn học') }}</label>
                                    <select name="subject_id" x-model="editing.subject_id">
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="field">
                                    <label>{{ __('Học kỳ') }}</label>
                                    <select name="semester" x-model="editing.semester">
                                        <option value="HK1-2025">HK1 - 2025</option>
                                        <option value="HK2-2025">HK2 - 2025</option>
                                        <option value="HK1-2026">HK1 - 2026</option>
                                        <option value="HK2-2026">HK2 - 2026</option>
                                    </select>
                                </div>

                                <div class="field">
                                    <label>{{ __('Miệng') }} (0-10)</label>
                                    <input type="number" name="oral_score" step="0.1" min="0" max="10" x-model="editing.oral_score" class="inp">
                                </div>

                                <div class="field">
                                    <label>{{ __('15 phút') }} (0-10)</label>
                                    <input type="number" name="quiz15_score" step="0.1" min="0" max="10" x-model="editing.quiz15_score" class="inp">
                                </div>

                                <div class="field">
                                    <label>{{ __('45 phút') }} (0-10)</label>
                                    <input type="number" name="test45_score" step="0.1" min="0" max="10" x-model="editing.test45_score" class="inp">
                                </div>

                                <div class="field">
                                    <label>{{ __('Cuối kỳ') }} (0-10)</label>
                                    <input type="number" name="final_score" step="0.1" min="0" max="10" x-model="editing.final_score" class="inp">
                                </div>
                            </div>
                        </div>

                        <div class="modal-f">
                            <button type="button" class="btn btn-ghost" @click="editing = null">{{ __('Hủy') }}</button>
                            <button type="submit" class="btn btn-accent">{{ __('Cập nhật') }}</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
@endsection
