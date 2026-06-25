@extends('layouts.app')

@section('title', __('Tổng quan'))

@section('content')
    @if (auth()->user()->isAdmin())
        <x-page-head crumb="{{ __('Tổng quan') }}" title="{{ __('Xin chào').', '.auth()->user()->name }}" sub="{{ __('Tổng quan hệ thống năm học 2025–2026') }}" />

        <div class="stats">
            <div class="stat">
                <div class="lab">{{ __('Tổng học sinh') }}</div>
                <div class="val">{{ $stats['students'] }}</div>
                <div class="nt">{{ __('đang theo học') }}</div>
            </div>
            <div class="stat">
                <div class="lab">{{ __('Lớp học') }}</div>
                <div class="val">{{ $stats['classrooms'] }}</div>
                <div class="nt">{{ __('đang hoạt động') }}</div>
            </div>
            <div class="stat">
                <div class="lab">{{ __('Môn học') }}</div>
                <div class="val">{{ $stats['subjects'] }}</div>
                <div class="nt">{{ __('trong chương trình') }}</div>
            </div>
            <div class="stat">
                <div class="lab">{{ __('Điểm trung bình toàn trường') }}</div>
                <div class="val">{{ number_format($stats['avg_score'], 1) }}</div>
                <div class="nt">{{ __('trên tất cả học kỳ') }}</div>
            </div>
        </div>

        <div class="grid-2 mt-[18px]">
            <div class="panel">
                <div class="panel-h">
                    <span class="ti">{{ __('Điểm trung bình theo lớp') }}</span>
                </div>
                <div class="chart-box">
                    <canvas
                        data-labels="{{ json_encode($classroomAverages->pluck('name')) }}"
                        data-scores="{{ json_encode($classroomAverages->pluck('avg_score')) }}"
                        x-data="{
                            init() {
                                new Chart(this.$el, {
                                    type: 'bar',
                                    data: {
                                        labels: JSON.parse(this.$el.dataset.labels),
                                        datasets: [{
                                            label: '{{ __('Điểm trung bình') }}',
                                            data: JSON.parse(this.$el.dataset.scores),
                                            backgroundColor: 'rgba(176,138,79,.55)',
                                            borderColor: '#b08a4f',
                                            borderWidth: 1,
                                            borderRadius: 8,
                                            maxBarThickness: 46,
                                        }],
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        scales: { y: { beginAtZero: true, max: 10 } },
                                        plugins: { legend: { display: false } },
                                    },
                                });
                            },
                        }"
                    ></canvas>
                </div>
            </div>

            <div class="panel">
                <div class="panel-h">
                    <span class="ti">{{ __('Top 5 học sinh điểm trung bình cao nhất') }}</span>
                </div>
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Học sinh') }}</th>
                            <th>{{ __('Lớp') }}</th>
                            <th>{{ __('Điểm TB') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topStudents as $i => $student)
                            <tr>
                                <td class="id">{{ $i + 1 }}</td>
                                <td class="cell-name">
                                    <x-avatar :name="$student->name" :size="30" />
                                    <span class="nm">{{ $student->name }}</span>
                                </td>
                                <td><span class="badge badge-cls">{{ $student->classroom_name }}</span></td>
                                <td><span class="avg-pill">{{ number_format($student->avg_score, 1) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4"><div class="empty"><div class="big">{{ __('Chưa có dữ liệu điểm') }}</div></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel mt-[18px]">
            <div class="panel-h">
                <span class="ti">{{ __('Xếp loại học lực theo lớp') }}</span>
                <div class="right ct">{{ $latestSemester ? __('Học kỳ').' '.$latestSemester : __('Chưa có dữ liệu') }}</div>
            </div>
            <table class="tbl">
                <thead>
                    <tr>
                        <th>{{ __('Lớp') }}</th>
                        <th>{{ __('Xuất sắc') }} (≥8.0)</th>
                        <th>{{ __('Giỏi') }} (≥6.5)</th>
                        <th>{{ __('Khá') }} (≥5.0)</th>
                        <th>{{ __('Yếu') }} (&lt;5.0)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($performanceByClassroom as $row)
                        <tr>
                            <td class="cell-name"><span class="nm">{{ $row['name'] }}</span></td>
                            <td><span class="badge badge-good">{{ $row['excellent'] }}</span></td>
                            <td><span class="badge badge-fair">{{ $row['good'] }}</span></td>
                            <td><span class="badge badge-avg">{{ $row['fair'] }}</span></td>
                            <td><span class="badge badge-weak">{{ $row['weak'] }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="empty"><div class="big">{{ __('Chưa có dữ liệu điểm trong học kỳ gần nhất') }}</div></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="grid-2 mt-[18px]">
            <div class="panel">
                <div class="panel-h">
                    <span class="ti">{{ __('Học sinh') }}</span>
                    <div class="right"><a href="{{ route('students.index') }}" class="btn btn-ghost btn-sm">{{ __('Xem tất cả') }}</a></div>
                </div>
                <div style="padding:22px;color:var(--ink-soft);font-size:14px">{{ __('Quản lý hồ sơ học sinh và phân lớp.') }}</div>
            </div>
            <div class="panel">
                <div class="panel-h">
                    <span class="ti">{{ __('Điểm số') }}</span>
                    <div class="right"><a href="{{ route('grades.index') }}" class="btn btn-ghost btn-sm">{{ __('Xem tất cả') }}</a></div>
                </div>
                <div style="padding:22px;color:var(--ink-soft);font-size:14px">{{ __('Theo dõi điểm số theo môn và học kỳ.') }}</div>
            </div>
        </div>
    @else
        <x-page-head crumb="{{ __('Tổng quan') }}" title="{{ __('Xin chào').', '.auth()->user()->name }}" sub="{{ __('Đây là khu vực giảng dạy của bạn.') }}" />

        <div class="stats" style="grid-template-columns:repeat(3,1fr)">
            <div class="stat">
                <div class="lab">{{ __('Bộ môn đang dạy') }}</div>
                <div class="val">{{ $teacherStats['subjects'] }}</div>
                <div class="nt">{{ $teacherStats['classCount'] }} {{ __('lớp') }}</div>
            </div>
            <div class="stat">
                <div class="lab">{{ __('Lớp đang phụ trách') }}</div>
                <div class="val">{{ $teacherStats['classCount'] }}</div>
                <div class="nt">{{ __('đang giảng dạy') }}</div>
            </div>
            <div class="stat">
                <div class="lab">{{ __('Tiết dạy hôm nay') }}</div>
                <div class="val">{{ $teacherStats['todayCount'] }}</div>
                <div class="nt">{{ __('tiết') }}</div>
            </div>
        </div>

        <div class="grid-2 mt-[18px]">
            <div class="panel">
                <div class="panel-h">
                    <span class="ti">{{ __('Lịch dạy hôm nay') }}</span>
                    <span class="ct">{{ $todayDayName }} · {{ now()->format('d/m') }}</span>
                </div>
                <div>
                    @forelse ($todayLessons as $i => $lesson)
                        <div class="flex items-center gap-[16px]" style="padding:14px 22px;{{ $i < $todayLessons->count() - 1 ? 'border-bottom:1px solid var(--line)' : '' }}">
                            <div style="text-align:center;min-width:54px">
                                <div style="font-size:11px;color:var(--ink-faint)">{{ __('Tiết') }} {{ $lesson->period }}</div>
                                <div style="font-size:14px;font-weight:700;font-family:Spectral,serif">{{ \App\Models\Lesson::PERIOD_TIMES[$lesson->period] }}</div>
                            </div>
                            <div style="width:1px;align-self:stretch;background:var(--line)"></div>
                            <div style="flex:1">
                                <div style="font-weight:700">{{ $lesson->subject->name }}</div>
                                <div style="font-size:13px;color:var(--ink-soft)">{{ __('Lớp') }} {{ $lesson->classroom->name }}</div>
                            </div>
                            <a href="{{ route('grades.entry', ['classroom_id' => $lesson->classroom_id, 'subject_id' => $lesson->subject_id]) }}" class="btn btn-ghost btn-sm">{{ __('Vào nhập điểm') }}</a>
                        </div>
                    @empty
                        <div class="empty"><div class="big">{{ __('Không có tiết dạy nào hôm nay') }}</div></div>
                    @endforelse
                </div>
            </div>

            <div class="panel">
                <div class="panel-h"><span class="ti">{{ __('Lớp đang phụ trách') }}</span></div>
                <div style="padding:10px 0">
                    @forelse ($myClasses as $i => $row)
                        <div class="flex items-center gap-[13px]" style="padding:13px 22px;{{ $i < $myClasses->count() - 1 ? 'border-bottom:1px solid var(--line)' : '' }}">
                            <span style="width:40px;height:40px;border-radius:11px;background:var(--accent-soft);color:var(--accent-deep);display:flex;align-items:center;justify-content:center;font-weight:700;font-family:Spectral,serif;font-size:14px">{{ $row['classroom']->name }}</span>
                            <div style="flex:1">
                                <div style="font-weight:600;font-size:14px">{{ $row['classroom']->name }}</div>
                                <div style="font-size:12.5px;color:var(--ink-faint)">{{ $row['subjects'] }}</div>
                            </div>
                            <a href="{{ route('grades.entry', ['classroom_id' => $row['classroom']->id]) }}" class="btn btn-ghost btn-sm">{{ __('Vào nhập điểm') }}</a>
                        </div>
                    @empty
                        <div class="empty"><div class="big">{{ __('Chưa có lớp nào trong thời khóa biểu') }}</div></div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
@endsection
