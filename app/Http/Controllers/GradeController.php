<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;

class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::with(['student', 'subject'])->get();
        return view('grades.index', compact('grades'));
    }

    public function create()
    {
        $students = Student::all();
        $subjects = Subject::all();
        return view('grades.create', compact('students', 'subjects'));
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
                         ->with('success', 'Nhập điểm thành công!');
    }

    public function edit(Grade $grade)
    {
        $students = Student::all();
        $subjects = Subject::all();
        return view('grades.edit', compact('grade', 'students', 'subjects'));
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
                         ->with('success', 'Cập nhật điểm thành công!');
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('grades.index')
                         ->with('success', 'Xóa điểm thành công!');
    }
}
