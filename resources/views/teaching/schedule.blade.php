@extends('layouts.app')

@section('title', __('Thời khóa biểu'))

@section('content')
    <x-page-head crumb="{{ __('Giảng dạy').' · '.__('Thời khóa biểu') }}" title="{{ __('Thời khóa biểu') }}" sub="{{ __('Lịch dạy hàng tuần của bạn, do Admin xếp.') }}" />

    <div class="panel">
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
                                        <div class="slot filled mine">
                                            <div class="s-subj">{{ $lesson->subject->name }}</div>
                                            <div class="s-meta">{{ $lesson->classroom->name }}</div>
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
