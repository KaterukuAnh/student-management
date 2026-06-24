<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = max(1, min((int) $request->input('per_page', 10), 100));
        $students = Student::with('classroom')->paginate($perPage)->withQueryString();
        $classrooms = Classroom::all();
        return view('students.index', compact('students', 'classrooms'));
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
                         ->with('success', __('Thêm học sinh thành công!'));
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
                         ->with('success', __('Cập nhật thành công!'));
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')
                         ->with('success', __('Xóa thành công!'));
    }
}
