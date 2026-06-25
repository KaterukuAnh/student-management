@extends('layouts.app')

@section('title', __('Thời khóa biểu'))

@php
    $reopenCreate = $errors->any() && old('_modal') === 'create';
    $reopenEdit = $errors->any() && old('_modal') === 'edit';
    $currentClassroom = $classrooms->firstWhere('id', $classroomId);
@endphp

@section('content')
    <div
        x-data="{
            creating: null,
            editing: null,
            days: @js(collect(\App\Models\Lesson::DAYS)->mapWithKeys(fn ($label, $num) => [$num => __($label)])->all()),
        }"
        x-init="
            @if ($reopenCreate) creating = @js(['day' => (int) old('day'), 'period' => (int) old('period'), 'subject_id' => old('subject_id'), 'teacher_id' => old('teacher_id')]) @endif
            @if ($reopenEdit) editing = @js(['id' => old('id'), 'subject_id' => old('subject_id'), 'teacher_id' => old('teacher_id')]) @endif
        "
    >
        <x-page-head crumb="{{ __('Quản lý').' · '.__('Thời khóa biểu') }}" title="{{ __('Thời khóa biểu') }}" sub="{{ __('Xếp lịch dạy cho từng lớp trong tuần.') }}" />

        <div class="panel">
            <div class="panel-h" style="flex-wrap:wrap;gap:14px 18px">
                <span class="ti">{{ $currentClassroom?->name }}</span>
                <div class="right">
                    <div class="chips">
                        @forelse ($classrooms as $classroom)
                            <a href="{{ route('admin.schedule', ['classroom_id' => $classroom->id]) }}"
                               class="chip {{ $classroom->id === $classroomId ? 'on' : '' }}">{{ $classroom->name }}</a>
                        @empty
                            <span class="ct">{{ __('Chưa có lớp học nào') }}</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <div style="padding:22px;overflow-x:auto">
                <table class="sched">
                    <thead>
                        <tr>
                            <th class="corner">{{ __('Tiết') }}</th>
                            @foreach (\App\Models\Lesson::DAYS as $dayLabel)
                                <th>{{ __($dayLabel) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (\App\Models\Lesson::PERIOD_TIMES as $periodNum => $time)
                            <tr>
                                <td class="per">
                                    <span>{{ __('Tiết') }}</span>
                                    <b>{{ $periodNum }}</b>
                                    <span style="font-weight:500">{{ $time }}</span>
                                </td>
                                @foreach (\App\Models\Lesson::DAYS as $dayNum => $dayLabel)
                                    @php $lesson = $lessons->get($dayNum.'-'.$periodNum); @endphp
                                    <td>
                                        @if ($lesson)
                                            <div class="slot filled">
                                                <div class="s-subj">{{ $lesson->subject->name }}</div>
                                                <div class="s-meta">{{ $lesson->teacher->name }}</div>
                                                <div class="s-actions">
                                                    <button type="button" class="iconbtn" @click="editing = { id: {{ $lesson->id }}, subject_id: {{ $lesson->subject_id }}, teacher_id: {{ $lesson->teacher_id }} }" title="{{ __('Sửa') }}">✏</button>
                                                    <form action="{{ route('admin.schedule.destroy', $lesson) }}" method="POST" onsubmit="return confirm('{{ __('Xóa tiết dạy này?') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="iconbtn danger" title="{{ __('Xóa') }}">✕</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @else
                                            <div class="slot-empty" title="{{ __('Xếp tiết dạy') }}" @click="creating = { day: {{ $dayNum }}, period: {{ $periodNum }}, subject_id: '', teacher_id: '' }">+</div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="overlay" x-show="creating" x-cloak @keydown.escape.window="creating = null" style="display:none">
            <div class="modal" @click.away="creating = null" x-show="creating">
                <div class="modal-h">
                    <span class="ti">{{ __('Xếp tiết dạy') }}</span>
                    <button type="button" class="x" @click="creating = null">✕</button>
                </div>

                <template x-if="creating">
                    <form method="POST" action="{{ route('admin.schedule.store') }}">
                        @csrf
                        <input type="hidden" name="_modal" value="create">
                        <input type="hidden" name="classroom_id" value="{{ $classroomId }}">
                        <input type="hidden" name="day" :value="creating.day">
                        <input type="hidden" name="period" :value="creating.period">

                        <div class="modal-b">
                            @if ($reopenCreate)
                                <div class="login-error">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="ct" style="margin-bottom:16px" x-text="'{{ $currentClassroom?->name }}' + ' · ' + (days[creating.day] || '') + ' · {{ __('Tiết') }} ' + creating.period"></div>

                            <div class="form-grid">
                                <div class="field full">
                                    <label>{{ __('Môn học') }}</label>
                                    <select name="subject_id" x-model="creating.subject_id">
                                        <option value="">-- {{ __('Chọn môn') }} --</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="field full">
                                    <label>{{ __('Giáo viên') }}</label>
                                    <select name="teacher_id" x-model="creating.teacher_id">
                                        <option value="">-- {{ __('Chọn giáo viên') }} --</option>
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="modal-f">
                            <button type="button" class="btn btn-ghost" @click="creating = null">{{ __('Hủy') }}</button>
                            <button type="submit" class="btn btn-accent">{{ __('Lưu') }}</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>

        <div class="overlay" x-show="editing" x-cloak @keydown.escape.window="editing = null" style="display:none">
            <div class="modal" @click.away="editing = null" x-show="editing">
                <div class="modal-h">
                    <span class="ti">{{ __('Sửa tiết dạy') }}</span>
                    <button type="button" class="x" @click="editing = null">✕</button>
                </div>

                <template x-if="editing">
                    <form method="POST" :action="'{{ route('admin.schedule') }}/' + editing.id">
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
                                    <label>{{ __('Môn học') }}</label>
                                    <select name="subject_id" x-model="editing.subject_id">
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="field full">
                                    <label>{{ __('Giáo viên') }}</label>
                                    <select name="teacher_id" x-model="editing.teacher_id">
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
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
