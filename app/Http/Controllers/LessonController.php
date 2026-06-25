<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    // Thời khóa biểu cá nhân của giáo viên — chỉ xem, lịch do Admin xếp.
    public function index(Request $request)
    {
        $lessons = Lesson::with(['classroom', 'subject'])
            ->where('teacher_id', $request->user()->id)
            ->get()
            ->keyBy(fn (Lesson $lesson) => $lesson->day.'-'.$lesson->period);

        return view('teaching.schedule', compact('lessons'));
    }
}
