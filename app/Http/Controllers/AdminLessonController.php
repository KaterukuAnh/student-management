<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminLessonController extends Controller
{
    // Xếp thời khóa biểu toàn trường — Admin xem/xếp lịch theo từng lớp.
    public function index(Request $request)
    {
        $classrooms = Classroom::orderBy('name')->get();
        $classroomId = (int) $request->input('classroom_id', $classrooms->first()?->id);

        $lessons = Lesson::with(['subject', 'teacher'])
            ->where('classroom_id', $classroomId)
            ->get()
            ->keyBy(fn (Lesson $lesson) => $lesson->day.'-'.$lesson->period);

        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();

        return view('admin.schedule', compact('classrooms', 'classroomId', 'lessons', 'subjects', 'teachers'));
    }

    private function teacherRule(): array
    {
        return ['required', Rule::exists('users', 'id')->where('role', 'teacher')];
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'day' => 'required|integer|min:2|max:7',
            'period' => 'required|integer|min:1|max:5',
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => $this->teacherRule(),
        ]);

        $conflict = Lesson::where('day', $validated['day'])
            ->where('period', $validated['period'])
            ->where(function ($query) use ($validated) {
                $query->where('classroom_id', $validated['classroom_id'])
                    ->orWhere('teacher_id', $validated['teacher_id']);
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'period' => __('Tiết học này đã trùng với lịch dạy khác (trùng lớp hoặc trùng giáo viên).'),
            ])->withInput();
        }

        Lesson::create($validated);

        return redirect()->route('admin.schedule', ['classroom_id' => $validated['classroom_id']])
            ->with('success', __('Xếp tiết dạy thành công!'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => $this->teacherRule(),
        ]);

        $conflict = Lesson::where('day', $lesson->day)
            ->where('period', $lesson->period)
            ->where('id', '!=', $lesson->id)
            ->where('teacher_id', $validated['teacher_id'])
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'teacher_id' => __('Giáo viên này đã có tiết dạy khác cùng giờ.'),
            ])->withInput();
        }

        $lesson->update($validated);

        return redirect()->route('admin.schedule', ['classroom_id' => $lesson->classroom_id])
            ->with('success', __('Cập nhật tiết dạy thành công!'));
    }

    public function destroy(Lesson $lesson)
    {
        $classroomId = $lesson->classroom_id;
        $lesson->delete();

        return redirect()->route('admin.schedule', ['classroom_id' => $classroomId])
            ->with('success', __('Xóa tiết dạy thành công!'));
    }
}
