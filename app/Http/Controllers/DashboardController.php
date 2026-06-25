<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Subject;

class DashboardController extends Controller
{
    public function index()
    {
        if (! auth()->user()->isAdmin()) {
            return view('dashboard', $this->teacherDashboardData());
        }

        $latestSemester = $this->latestSemester();
        $allScores = Grade::all()->pluck('score')->filter(fn ($score) => $score !== null);

        return view('dashboard', [
            'stats' => [
                'students' => Student::count(),
                'classrooms' => Classroom::count(),
                'subjects' => Subject::count(),
                'avg_score' => $allScores->isNotEmpty() ? round($allScores->avg(), 1) : 0,
            ],
            'classroomAverages' => $this->classroomAverages(),
            'latestSemester' => $latestSemester,
            'performanceByClassroom' => $this->performanceByClassroom($latestSemester),
            'topStudents' => $this->topStudents(),
        ]);
    }

    // Điểm trung bình theo lớp cho biểu đồ cột — tính trên collection vì "score" là accessor
    private function classroomAverages()
    {
        return Classroom::with('students.grades')->orderBy('name')->get()
            ->map(function ($classroom) {
                $scores = $classroom->students
                    ->flatMap(fn ($student) => $student->grades)
                    ->pluck('score')
                    ->filter(fn ($score) => $score !== null);

                return [
                    'name' => $classroom->name,
                    'avg_score' => $scores->isNotEmpty() ? round($scores->avg(), 2) : 0.0,
                ];
            });
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

    // Số học sinh mỗi lớp theo mức học lực, dựa trên điểm TB học kỳ gần nhất
    private function performanceByClassroom(?string $latestSemester)
    {
        if (! $latestSemester) {
            return collect();
        }

        return Classroom::with(['students.grades' => fn ($query) => $query->where('semester', $latestSemester)])
            ->orderBy('name')->get()
            ->map(function ($classroom) {
                $studentAverages = $classroom->students->map(function ($student) {
                    $scores = $student->grades->pluck('score')->filter(fn ($score) => $score !== null);

                    return $scores->isNotEmpty() ? $scores->avg() : null;
                })->filter(fn ($avg) => $avg !== null);

                return [
                    'name' => $classroom->name,
                    'excellent' => $studentAverages->filter(fn ($avg) => $avg >= 8.0)->count(),
                    'good' => $studentAverages->filter(fn ($avg) => $avg >= 6.5 && $avg < 8.0)->count(),
                    'fair' => $studentAverages->filter(fn ($avg) => $avg >= 5.0 && $avg < 6.5)->count(),
                    'weak' => $studentAverages->filter(fn ($avg) => $avg < 5.0)->count(),
                ];
            });
    }

    // Top 5 học sinh có điểm trung bình (tất cả các môn, mọi học kỳ) cao nhất toàn trường
    private function topStudents()
    {
        return Student::with(['grades', 'classroom'])->get()
            ->map(function ($student) {
                $scores = $student->grades->pluck('score')->filter(fn ($score) => $score !== null);

                return (object) [
                    'name' => $student->name,
                    'classroom_name' => $student->classroom?->name,
                    'avg_score' => $scores->isNotEmpty() ? round($scores->avg(), 2) : null,
                ];
            })
            ->filter(fn ($row) => $row->avg_score !== null)
            ->sortByDesc('avg_score')
            ->take(5)
            ->values();
    }

    // Tổng quan giảng dạy của giáo viên, suy ra trực tiếp từ thời khóa biểu (Lesson)
    private function teacherDashboardData(): array
    {
        $teacher = auth()->user();

        $lessons = Lesson::with(['classroom', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->get();

        // isoWeekday(): 1 = Thứ 2 ... 7 = Chủ nhật. Lesson::DAYS chỉ có 2-7 (Thứ 2 - Thứ 7), không dạy Chủ nhật.
        $isoWeekday = now()->isoWeekday();
        $todayDay = $isoWeekday === 7 ? null : $isoWeekday + 1;
        $todayDayName = $todayDay ? __(Lesson::DAYS[$todayDay]) : __('Chủ nhật');

        $todayLessons = $lessons->where('day', $todayDay)->sortBy('period')->values();

        $myClasses = $lessons->groupBy('classroom_id')
            ->map(fn ($group) => [
                'classroom' => $group->first()->classroom,
                'subjects' => $group->pluck('subject.name')->unique()->implode(', '),
            ])
            ->sortBy(fn ($row) => $row['classroom']->name)
            ->values();

        return [
            'teacherStats' => [
                'subjects' => $lessons->pluck('subject.name')->unique()->implode(', ') ?: '—',
                'classCount' => $myClasses->count(),
                'todayCount' => $todayLessons->count(),
            ],
            'todayDayName' => $todayDayName,
            'todayLessons' => $todayLessons,
            'myClasses' => $myClasses,
        ];
    }
}
