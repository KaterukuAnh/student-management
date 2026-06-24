<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    // Danh sách lớp
    public function index(Request $request)
    {
        $perPage = max(1, min((int) $request->input('per_page', 10), 100));
        $classrooms = Classroom::paginate($perPage)->withQueryString();
        return view('classrooms.index', compact('classrooms'));
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
                         ->with('success', __('Tạo lớp thành công!'));
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
                         ->with('success', __('Cập nhật thành công!'));
    }

    // Xóa
    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return redirect()->route('classrooms.index')
                         ->with('success', __('Xóa thành công!'));
    }
}
