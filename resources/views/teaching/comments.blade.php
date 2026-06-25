@extends('layouts.app')

@section('title', __('Nhận xét học sinh'))

@section('content')
    <x-page-head crumb="{{ __('Giảng dạy').' · '.__('Nhận xét') }}" title="{{ __('Nhận xét học sinh') }}" sub="{{ __('Chọn lớp và học sinh để xem, ghi nhận xét.') }}" />

    @if ($errors->any())
        <div class="login-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="panel" style="margin-bottom:18px">
        <div class="panel-h" style="flex-wrap:wrap;gap:14px 18px">
            <span class="ct" style="font-weight:600;color:var(--ink-soft)">{{ __('Lớp') }}</span>
            <div class="chips">
                @forelse ($classrooms as $classroom)
                    <a href="{{ route('comments', ['classroom_id' => $classroom->id]) }}"
                       class="chip {{ $classroom->id === $classroomId ? 'on' : '' }}">{{ $classroom->name }}</a>
                @empty
                    <span class="ct">{{ __('Chưa có lớp học nào') }}</span>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid-2" style="grid-template-columns:300px 1fr">
        <div class="panel">
            <div class="panel-h"><span class="ti">{{ __('Chọn học sinh') }}</span></div>
            <div style="padding:8px 0;max-height:520px;overflow-y:auto">
                @forelse ($students as $student)
                    <a href="{{ route('comments', ['classroom_id' => $classroomId, 'student_id' => $student->id]) }}"
                       style="width:100%;display:flex;align-items:center;gap:11px;padding:11px 22px;text-decoration:none;color:inherit;background:{{ $student->id === $studentId ? 'var(--field-bg)' : 'none' }};border-left:3px solid {{ $student->id === $studentId ? 'var(--accent)' : 'transparent' }}">
                        <x-avatar :name="$student->name" :size="34" />
                        <div style="min-width:0">
                            <div style="font-size:14px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $student->name }}</div>
                            <div style="font-size:12px;color:var(--ink-faint);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $student->email }}</div>
                        </div>
                    </a>
                @empty
                    <div class="empty"><div class="big">{{ __('Lớp này chưa có học sinh') }}</div></div>
                @endforelse
            </div>
        </div>

        <div class="panel">
            @if ($selectedStudent)
                <div class="panel-h">
                    <span class="cell-name">
                        <x-avatar :name="$selectedStudent->name" :size="38" />
                        <span class="ti">{{ $selectedStudent->name }}</span>
                    </span>
                    <span class="ct">{{ $selectedClassroomName }}</span>
                </div>

                <div style="padding:22px" x-data="{ conduct: '{{ old('conduct', 'good') }}' }">
                    <form method="POST" action="{{ route('comments.store') }}">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $selectedStudent->id }}">
                        <input type="hidden" name="conduct" x-model="conduct">

                        <div class="field">
                            <label>{{ __('Hạnh kiểm') }}</label>
                            <div class="seg">
                                @foreach (\App\Models\Comment::CONDUCTS as $value => $label)
                                    <button type="button" :class="{ on: conduct === '{{ $value }}' }" @click="conduct = '{{ $value }}'">{{ __($label) }}</button>
                                @endforeach
                            </div>
                        </div>

                        <div style="height:18px"></div>

                        <div class="field">
                            <label>{{ __('Nhận xét của giáo viên') }}</label>
                            <textarea name="content" placeholder="{{ __('Viết nhận xét về quá trình học tập và rèn luyện…') }}">{{ old('content') }}</textarea>
                        </div>

                        <div style="margin-top:18px;text-align:right">
                            <button type="submit" class="btn btn-accent">{{ __('Lưu nhận xét') }}</button>
                        </div>
                    </form>
                </div>

                <div style="border-top:1px solid var(--line)">
                    <div class="panel-h"><span class="ti">{{ __('Lịch sử nhận xét') }}</span></div>
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>{{ __('Ngày') }}</th>
                                <th>{{ __('Giáo viên') }}</th>
                                <th>{{ __('Hạnh kiểm') }}</th>
                                <th>{{ __('Nội dung') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($comments as $comment)
                                <tr>
                                    <td>{{ $comment->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $comment->teacher->name }}</td>
                                    <td>
                                        <span class="badge {{ $comment->conduct === 'good' ? 'badge-good' : ($comment->conduct === 'fair' ? 'badge-fair' : 'badge-avg') }}">
                                            {{ __(\App\Models\Comment::CONDUCTS[$comment->conduct] ?? $comment->conduct) }}
                                        </span>
                                    </td>
                                    <td>{{ $comment->content }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4"><div class="empty"><div class="big">{{ __('Chưa có nhận xét nào') }}</div></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty"><div class="big">{{ __('Lớp này chưa có học sinh') }}</div></div>
            @endif
        </div>
    </div>
@endsection
