<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $perPage = max(1, min((int) $request->input('per_page', 10), 100));
        $grades = Grade::with(['student', 'subject'])->paginate($perPage)->withQueryString();
        $students = Student::all();
        $subjects = Subject::all();
        return view('grades.index', compact('grades', 'students', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'score'      => 'required|numeric|min:0|max:10',
            'semester'   => 'required',
        ]);

        Grade::create($request->all());
        return redirect()->route('grades.index')
                         ->with('success', __('Nhập điểm thành công!'));
    }

    public function update(Request $request, Grade $grade)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'score'      => 'required|numeric|min:0|max:10',
            'semester'   => 'required',
        ]);

        $grade->update($request->all());
        return redirect()->route('grades.index')
                         ->with('success', __('Cập nhật điểm thành công!'));
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('grades.index')
                         ->with('success', __('Xóa điểm thành công!'));
    }
}
