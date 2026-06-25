@extends('layouts.app')

@section('title', __('Nhập điểm'))

@section('content')
    <x-page-head crumb="{{ __('Giảng dạy').' · '.__('Nhập điểm') }}" title="{{ __('Nhập điểm') }}" sub="{{ __('Chọn lớp, môn học và học kỳ rồi nhập điểm cho từng học sinh.') }}">
        <x-slot:actions>
            <button type="submit" form="grade-entry-form" class="btn btn-primary">{{ __('Lưu bảng điểm') }}</button>
        </x-slot:actions>
    </x-page-head>

    @if ($errors->any())
        <div class="login-error" style="margin-bottom:16px">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="panel">
        <div class="panel-h" style="flex-wrap:wrap;gap:14px 18px">
            <span class="ct" style="font-weight:600;color:var(--ink-soft)">{{ __('Lớp') }}</span>
            <div class="chips">
                @forelse ($classrooms as $classroom)
                    <a href="{{ route('grades.entry', ['classroom_id' => $classroom->id, 'subject_id' => $subjectId, 'semester' => $semester]) }}"
                       class="chip {{ $classroom->id === $classroomId ? 'on' : '' }}">{{ $classroom->name }}</a>
                @empty
                    <span class="ct">{{ __('Chưa có lớp học nào') }}</span>
                @endforelse
            </div>
        </div>

        <div class="panel-h" style="flex-wrap:wrap;gap:14px 18px;border-top:0">
            <span class="ct" style="font-weight:600;color:var(--ink-soft)">{{ __('Môn') }}</span>
            <div class="chips">
                @forelse ($subjects as $subject)
                    <a href="{{ route('grades.entry', ['classroom_id' => $classroomId, 'subject_id' => $subject->id, 'semester' => $semester]) }}"
                       class="chip {{ $subject->id === $subjectId ? 'on' : '' }}">{{ $subject->name }}</a>
                @empty
                    <span class="ct">{{ __('Chưa có môn học nào') }}</span>
                @endforelse
            </div>
        </div>

        <div class="panel-h" style="flex-wrap:wrap;gap:14px 18px;border-top:0">
            <span class="ct" style="font-weight:600;color:var(--ink-soft)">{{ __('Học kỳ') }}</span>
            <div class="chips">
                @foreach ($semesters as $sem)
                    <a href="{{ route('grades.entry', ['classroom_id' => $classroomId, 'subject_id' => $subjectId, 'semester' => $sem]) }}"
                       class="chip {{ $sem === $semester ? 'on' : '' }}">{{ $sem }}</a>
                @endforeach
            </div>
            <div class="right ct">{{ __('TB tính theo hệ số: Miệng x1, 15′ x1, 45′ x2, Cuối kỳ x3') }}</div>
        </div>

        <form method="POST" action="{{ route('grades.entry.store') }}" id="grade-entry-form">
            @csrf
            <input type="hidden" name="classroom_id" value="{{ $classroomId }}">
            <input type="hidden" name="subject_id" value="{{ $subjectId }}">
            <input type="hidden" name="semester" value="{{ $semester }}">

            <table class="tbl">
                <thead>
                    <tr>
                        <th>{{ __('Học sinh') }}</th>
                        <th style="text-align:center">{{ __('Miệng') }}</th>
                        <th style="text-align:center">{{ __('15 phút') }}</th>
                        <th style="text-align:center">{{ __('45 phút') }}</th>
                        <th style="text-align:center">{{ __('Cuối kỳ') }}</th>
                        <th style="text-align:center">{{ __('TB') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $i => $student)
                        @php
                            $grade = $grades->get($student->id);
                            $oral = old("scores.$i.oral_score", $grade?->oral_score);
                            $quiz15 = old("scores.$i.quiz15_score", $grade?->quiz15_score);
                            $test45 = old("scores.$i.test45_score", $grade?->test45_score);
                            $final = old("scores.$i.final_score", $grade?->final_score);
                        @endphp
                        <tr
                            data-oral="{{ $oral }}"
                            data-quiz15="{{ $quiz15 }}"
                            data-test45="{{ $test45 }}"
                            data-final="{{ $final }}"
                            x-data="{
                                oral: null,
                                quiz15: null,
                                test45: null,
                                final: null,
                                init() {
                                    this.oral = this.$el.dataset.oral === '' ? null : this.$el.dataset.oral;
                                    this.quiz15 = this.$el.dataset.quiz15 === '' ? null : this.$el.dataset.quiz15;
                                    this.test45 = this.$el.dataset.test45 === '' ? null : this.$el.dataset.test45;
                                    this.final = this.$el.dataset.final === '' ? null : this.$el.dataset.final;
                                },
                                get avg() {
                                    const parts = [[this.oral, 1], [this.quiz15, 1], [this.test45, 2], [this.final, 3]];
                                    let sum = 0, weight = 0;
                                    for (const [v, w] of parts) {
                                        const n = parseFloat(v);
                                        if (v !== null && v !== '' && !Number.isNaN(n)) { sum += n * w; weight += w; }
                                    }
                                    return weight > 0 ? (sum / weight).toFixed(1) : null;
                                },
                            }"
                        >
                            <td class="cell-name">
                                <input type="hidden" name="scores[{{ $i }}][student_id]" value="{{ $student->id }}">
                                <x-avatar :name="$student->name" :size="34" />
                                <span class="nm">{{ $student->name }}</span>
                            </td>
                            <td style="text-align:center"><input type="number" inputmode="decimal" step="0.1" min="0" max="10" class="gradeinp" name="scores[{{ $i }}][oral_score]" x-model="oral"></td>
                            <td style="text-align:center"><input type="number" inputmode="decimal" step="0.1" min="0" max="10" class="gradeinp" name="scores[{{ $i }}][quiz15_score]" x-model="quiz15"></td>
                            <td style="text-align:center"><input type="number" inputmode="decimal" step="0.1" min="0" max="10" class="gradeinp" name="scores[{{ $i }}][test45_score]" x-model="test45"></td>
                            <td style="text-align:center"><input type="number" inputmode="decimal" step="0.1" min="0" max="10" class="gradeinp" name="scores[{{ $i }}][final_score]" x-model="final"></td>
                            <td style="text-align:center"><span class="avg-pill" :class="{ empty: avg === null }" x-text="avg ?? '—'"></span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty"><div class="big">{{ __('Lớp này chưa có học sinh') }}</div></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>
@endsection
