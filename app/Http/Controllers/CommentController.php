<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Comment;
use App\Models\Student;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Giáo viên chọn lớp rồi chọn học sinh để xem/ghi nhận xét.
    public function index(Request $request)
    {
        $classrooms = Classroom::orderBy('name')->get();
        $classroomId = (int) $request->input('classroom_id', $classrooms->first()?->id);

        $students = Student::where('classroom_id', $classroomId)->orderBy('name')->get();
        $studentId = (int) $request->input('student_id', $students->first()?->id);
        $selectedStudent = $students->firstWhere('id', $studentId);
        $selectedClassroomName = $classrooms->firstWhere('id', $classroomId)?->name;

        $comments = $selectedStudent
            ? Comment::with('teacher')->where('student_id', $selectedStudent->id)->latest()->get()
            : collect();

        return view('teaching.comments', compact(
            'classrooms', 'classroomId', 'students', 'studentId',
            'selectedStudent', 'selectedClassroomName', 'comments'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'conduct' => 'required|in:good,fair,avg',
            'content' => 'required|string',
        ]);

        Comment::create([...$validated, 'teacher_id' => $request->user()->id]);

        $student = Student::findOrFail($validated['student_id']);

        return redirect()->route('comments', ['classroom_id' => $student->classroom_id, 'student_id' => $student->id])
            ->with('success', __('Lưu nhận xét thành công!'));
    }
}
