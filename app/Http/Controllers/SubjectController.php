<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $perPage = max(1, min((int) $request->input('per_page', 10), 100));
        $subjects = Subject::paginate($perPage)->withQueryString();
        return view('subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|min:2',
            'credits' => 'required|integer|min:1',
        ]);

        Subject::create($request->all());
        return redirect()->route('subjects.index')
                         ->with('success', __('Thêm môn học thành công!'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name'    => 'required|min:2',
            'credits' => 'required|integer|min:1',
        ]);

        $subject->update($request->all());
        return redirect()->route('subjects.index')
                         ->with('success', __('Cập nhật thành công!'));
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('subjects.index')
                         ->with('success', __('Xóa thành công!'));
    }
}
