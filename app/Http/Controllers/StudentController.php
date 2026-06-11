<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('classroom')->get();
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $classrooms = Classroom::all();
        return view('students.create', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|min:2',
            'birth_date'   => 'required|date',
            'gender'       => 'required',
            'email'        => 'required|email|unique:students',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        Student::create($request->all());
        return redirect()->route('students.index')
                         ->with('success', 'Thêm học sinh thành công!');
    }

    public function edit(Student $student)
    {
        $classrooms = Classroom::all();
        return view('students.edit', compact('student', 'classrooms'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name'         => 'required|min:2',
            'birth_date'   => 'required|date',
            'gender'       => 'required',
            'email'        => 'required|email|unique:students,email,' . $student->id,
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        $student->update($request->all());
        return redirect()->route('students.index')
                         ->with('success', 'Cập nhật thành công!');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')
                         ->with('success', 'Xóa thành công!');
    }
}
