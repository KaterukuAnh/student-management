<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassroomController extends Controller
{
    // Danh sách lớp, dạng card lưới (sĩ số, phòng học, GV chủ nhiệm)
    public function index(Request $request)
    {
        $perPage = max(1, min((int) $request->input('per_page', 10), 100));
        $search = $request->input('search', '');

        $query = Classroom::withCount('students')->with('homeroomTeacher');

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        $classrooms = $query->paginate($perPage)->withQueryString();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();

        return view('classrooms.index', compact('classrooms', 'teachers', 'search'));
    }

    private function homeroomTeacherRule(): array
    {
        return ['nullable', Rule::exists('users', 'id')->where('role', 'teacher')];
    }

    // Lưu vào DB
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2',
            'grade' => 'required',
            'room' => 'nullable|string|max:50',
            'homeroom_teacher_id' => $this->homeroomTeacherRule(),
        ]);

        Classroom::create($request->all());

        return redirect()->route('classrooms.index')
            ->with('success', __('Tạo lớp thành công!'));
    }

    // Cập nhật
    public function update(Request $request, Classroom $classroom)
    {
        $request->validate([
            'name' => 'required|min:2',
            'grade' => 'required',
            'room' => 'nullable|string|max:50',
            'homeroom_teacher_id' => $this->homeroomTeacherRule(),
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
