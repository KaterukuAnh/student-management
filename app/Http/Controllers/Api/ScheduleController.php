<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // Thời khóa biểu của lớp mà học sinh đang đăng nhập theo học.
    public function index(Request $request)
    {
        $student = $request->user()->student;

        abort_if(! $student, 404, __('Tài khoản chưa liên kết với hồ sơ học sinh.'));

        $lessons = Lesson::with(['subject', 'teacher'])
            ->where('classroom_id', $student->classroom_id)
            ->orderBy('day')
            ->orderBy('period')
            ->get()
            ->map(fn (Lesson $lesson) => [
                'day' => $lesson->day,
                'day_label' => __(Lesson::DAYS[$lesson->day]),
                'period' => $lesson->period,
                'time' => Lesson::PERIOD_TIMES[$lesson->period],
                'subject' => $lesson->subject->name,
                'teacher' => $lesson->teacher->name,
            ]);

        return response()->json(['data' => $lessons]);
    }
}
