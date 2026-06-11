<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    // Danh sách lớp
    public function index()
    {
        $classrooms = Classroom::all();
        return view('classrooms.index', compact('classrooms'));
    }

    // Form tạo mới
    public function create()
    {
        return view('classrooms.create');
    }

    // Lưu vào DB
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|min:2',
            'grade' => 'required',
        ]);

        Classroom::create($request->all());
        return redirect()->route('classrooms.index')
                         ->with('success', 'Tạo lớp thành công!');
    }

    // Form sửa
    public function edit(Classroom $classroom)
    {
        return view('classrooms.edit', compact('classroom'));
    }

    // Cập nhật
    public function update(Request $request, Classroom $classroom)
    {
        $request->validate([
            'name'  => 'required|min:2',
            'grade' => 'required',
        ]);

        $classroom->update($request->all());
        return redirect()->route('classrooms.index')
                         ->with('success', 'Cập nhật thành công!');
    }

    // Xóa
    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return redirect()->route('classrooms.index')
                         ->with('success', 'Xóa thành công!');
    }
}
