<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    // Điểm thành phần dùng chung cho validate ở store/update/bulkStore.
    private const SCORE_FIELDS = ['oral_score', 'quiz15_score', 'test45_score', 'final_score'];

    private function scoreRules(): array
    {
        return [
            'oral_score' => 'nullable|numeric|min:0|max:10|required_without_all:quiz15_score,test45_score,final_score',
            'quiz15_score' => 'nullable|numeric|min:0|max:10|required_without_all:oral_score,test45_score,final_score',
            'test45_score' => 'nullable|numeric|min:0|max:10|required_without_all:oral_score,quiz15_score,final_score',
            'final_score' => 'nullable|numeric|min:0|max:10|required_without_all:oral_score,quiz15_score,test45_score',
        ];
    }

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
        $validated = $request->validate(array_merge([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'semester' => 'required',
        ], $this->scoreRules()));

        Grade::create($validated);

        return redirect()->route('grades.index')
            ->with('success', __('Nhập điểm thành công!'));
    }

    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate(array_merge([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'semester' => 'required',
        ], $this->scoreRules()));

        $grade->update($validated);

        return redirect()->route('grades.index')
            ->with('success', __('Cập nhật điểm thành công!'));
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();

        return redirect()->route('grades.index')
            ->with('success', __('Xóa điểm thành công!'));
    }

    // Bảng nhập điểm theo lớp (giao diện chấm điểm dành cho giáo viên).
    public function entry(Request $request)
    {
        $classrooms = Classroom::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $semesters = ['HK1-2025', 'HK2-2025', 'HK1-2026', 'HK2-2026'];

        $classroomId = (int) $request->input('classroom_id', $classrooms->first()?->id);
        $subjectId = (int) $request->input('subject_id', $subjects->first()?->id);
        $semester = $request->input('semester', $semesters[0]);

        $students = Student::where('classroom_id', $classroomId)->orderBy('name')->get();

        $grades = Grade::where('subject_id', $subjectId)
            ->where('semester', $semester)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        return view('grades.entry', compact(
            'classrooms', 'subjects', 'semesters',
            'classroomId', 'subjectId', 'semester',
            'students', 'grades'
        ));
    }

    // Lưu toàn bộ điểm của một lớp trong 1 lần submit.
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'semester' => 'required|string',
            'scores' => 'required|array',
            'scores.*.student_id' => 'required|exists:students,id',
            'scores.*.oral_score' => 'nullable|numeric|min:0|max:10',
            'scores.*.quiz15_score' => 'nullable|numeric|min:0|max:10',
            'scores.*.test45_score' => 'nullable|numeric|min:0|max:10',
            'scores.*.final_score' => 'nullable|numeric|min:0|max:10',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['scores'] as $row) {
                $scoreValues = array_intersect_key($row, array_flip(self::SCORE_FIELDS));

                // Bỏ qua dòng học sinh chưa nhập điểm nào để không tạo bản ghi rỗng.
                if (count(array_filter($scoreValues, fn ($value) => $value !== null && $value !== '')) === 0) {
                    continue;
                }

                Grade::updateOrCreate(
                    [
                        'student_id' => $row['student_id'],
                        'subject_id' => $validated['subject_id'],
                        'semester' => $validated['semester'],
                    ],
                    $scoreValues
                );
            }
        });

        return redirect()->route('grades.entry', [
            'classroom_id' => $validated['classroom_id'],
            'subject_id' => $validated['subject_id'],
            'semester' => $validated['semester'],
        ])->with('success', __('Lưu bảng điểm thành công!'));
    }
}
