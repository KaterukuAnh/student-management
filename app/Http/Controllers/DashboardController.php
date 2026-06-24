<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;

class DashboardController extends Controller
{
    public function index()
    {
        if (! auth()->user()->isAdmin()) {
            return view('dashboard');
        }

        $latestSemester = $this->latestSemester();

        return view('dashboard', [
            'stats' => [
                'students' => Student::count(),
                'classrooms' => Classroom::count(),
                'subjects' => Subject::count(),
                'avg_score' => round(Grade::avg('score') ?? 0, 1),
            ],
            'classroomAverages' => $this->classroomAverages(),
            'latestSemester' => $latestSemester,
            'performanceByClassroom' => $this->performanceByClassroom($latestSemester),
            'topStudents' => $this->topStudents(),
        ]);
    }

    // Điểm trung bình các môn theo từng lớp, cho biểu đồ cột
    private function classroomAverages()
    {
        return Classroom::query()
            ->leftJoin('students', 'students.classroom_id', '=', 'classrooms.id')
            ->leftJoin('grades', 'grades.student_id', '=', 'students.id')
            ->select('classrooms.name')
            ->selectRaw('ROUND(AVG(grades.score), 2) as avg_score')
            ->groupBy('classrooms.id', 'classrooms.name')
            ->orderBy('classrooms.name')
            ->get()
            ->map(fn ($row) => [
                'name' => $row->name,
                'avg_score' => (float) $row->avg_score,
            ]);
    }

    // Học kỳ gần nhất có dữ liệu điểm, dựa trên format "HK{1|2}-{năm}"
    private function latestSemester(): ?string
    {
        return Grade::query()
            ->distinct()
            ->pluck('semester')
            ->sortBy(function ($semester) {
                preg_match('/^HK(\d)-(\d{4})$/', $semester, $matches);

                return isset($matches[2]) ? ((int) $matches[2]) * 10 + (int) $matches[1] : 0;
            })
            ->last();
    }

    // Số học sinh mỗi lớp theo mức học lực (Xuất sắc/Giỏi/Khá/Yếu),
    // tính trên điểm trung bình các môn của từng học sinh trong học kỳ gần nhất
    private function performanceByClassroom(?string $latestSemester)
    {
        if (! $latestSemester) {
            return collect();
        }

        $studentAverages = Grade::query()
            ->join('students', 'students.id', '=', 'grades.student_id')
            ->where('grades.semester', $latestSemester)
            ->select('students.classroom_id')
            ->selectRaw('students.id as student_id, AVG(grades.score) as avg_score')
            ->groupBy('students.id', 'students.classroom_id')
            ->get()
            ->map(fn ($row) => [
                'classroom_id' => $row->classroom_id,
                'avg_score' => (float) $row->avg_score,
            ]);

        return Classroom::orderBy('name')->get()->map(function ($classroom) use ($studentAverages) {
            $inClass = $studentAverages->where('classroom_id', $classroom->id);

            return [
                'name' => $classroom->name,
                'excellent' => $inClass->where('avg_score', '>=', 8.0)->count(),
                'good' => $inClass->where('avg_score', '>=', 6.5)->where('avg_score', '<', 8.0)->count(),
                'fair' => $inClass->where('avg_score', '>=', 5.0)->where('avg_score', '<', 6.5)->count(),
                'weak' => $inClass->where('avg_score', '<', 5.0)->count(),
            ];
        });
    }

    // Top 5 học sinh có điểm trung bình (tất cả các môn, mọi học kỳ) cao nhất toàn trường
    private function topStudents()
    {
        return Student::query()
            ->join('grades', 'grades.student_id', '=', 'students.id')
            ->join('classrooms', 'classrooms.id', '=', 'students.classroom_id')
            ->select('students.name', 'classrooms.name as classroom_name')
            ->selectRaw('ROUND(AVG(grades.score), 2) as avg_score')
            ->groupBy('students.id', 'students.name', 'classrooms.name')
            ->orderByDesc('avg_score')
            ->limit(5)
            ->get();
    }
}
