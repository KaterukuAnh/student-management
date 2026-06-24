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

        <div class="cards-grid">
            <div class="panel" style="padding:22px">
                <div class="ti" style="font-size:15.5px;font-weight:700;margin-bottom:8px">{{ __('Nhập điểm') }}</div>
                <div style="color:var(--ink-soft);font-size:13.5px;margin-bottom:16px">{{ __('Ghi nhận điểm số cho học sinh theo môn học.') }}</div>
                <a href="{{ route('grades.index') }}" class="btn btn-accent btn-sm">{{ __('Đi tới Nhập điểm') }}</a>
            </div>
            <div class="panel" style="padding:22px">
                <div class="ti" style="font-size:15.5px;font-weight:700;margin-bottom:8px">{{ __('Thời khóa biểu') }}</div>
                <div style="color:var(--ink-soft);font-size:13.5px;margin-bottom:16px">{{ __('Xem lịch dạy của bạn trong tuần.') }}</div>
                <a href="{{ route('schedule') }}" class="btn btn-ghost btn-sm">{{ __('Xem lịch dạy') }}</a>
            </div>
            <div class="panel" style="padding:22px">
                <div class="ti" style="font-size:15.5px;font-weight:700;margin-bottom:8px">{{ __('Nhận xét học sinh') }}</div>
                <div style="color:var(--ink-soft);font-size:13.5px;margin-bottom:16px">{{ __('Viết nhận xét về quá trình học tập và rèn luyện.') }}</div>
                <a href="{{ route('comments') }}" class="btn btn-ghost btn-sm">{{ __('Viết nhận xét') }}</a>
            </div>
        </div>
    @endif
@endsection
