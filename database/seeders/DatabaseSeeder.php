<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Comment;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tài khoản mặc định đã có sẵn — chỉ tạo nếu chưa tồn tại, không đụng tới nếu đã có.
        if (! User::where('email', 'admin@academie.edu.vn')->exists()) {
            User::factory()->admin()->create([
                'name' => 'Quản trị viên',
                'email' => 'admin@academie.edu.vn',
            ]);
        }

        if (! User::where('email', 'teacher@academie.edu.vn')->exists()) {
            User::factory()->teacher()->create([
                'name' => 'Lê Thu Hà',
                'email' => 'teacher@academie.edu.vn',
            ]);
        }

        // 3 giáo viên mới
        $newTeachers = [
            ['name' => 'Nguyễn Thị Lan', 'email' => 'lan.toan@academie.edu.vn'],
            ['name' => 'Trần Văn Bình', 'email' => 'binh.van@academie.edu.vn'],
            ['name' => 'Phạm Thị Hồng', 'email' => 'hong.anh@academie.edu.vn'],
        ];

        foreach ($newTeachers as $teacher) {
            User::firstOrCreate(
                ['email' => $teacher['email']],
                [
                    'name' => $teacher['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'role' => 'teacher',
                ]
            );
        }

        // 3 lớp học, kèm phòng học và GV chủ nhiệm để demo trang "Lớp học" dạng card.
        $classroomsData = [
            '10A1' => ['grade' => '10', 'room' => 'A.101', 'homeroom_email' => 'teacher@academie.edu.vn'],
            '11A2' => ['grade' => '11', 'room' => 'B.205', 'homeroom_email' => 'lan.toan@academie.edu.vn'],
            '12A3' => ['grade' => '12', 'room' => 'C.310', 'homeroom_email' => 'binh.van@academie.edu.vn'],
        ];

        $classrooms = [];
        foreach ($classroomsData as $name => $data) {
            $homeroomTeacher = User::where('email', $data['homeroom_email'])->first();

            $classrooms[$name] = Classroom::firstOrCreate(
                ['name' => $name],
                ['grade' => $data['grade'], 'room' => $data['room'], 'homeroom_teacher_id' => $homeroomTeacher->id]
            );
        }

        // 5 môn học
        $subjectsData = [
            'Toán' => 4,
            'Ngữ Văn' => 3,
            'Tiếng Anh' => 3,
            'Vật Lý' => 2,
            'Hóa Học' => 2,
        ];

        $subjects = [];
        foreach ($subjectsData as $name => $credits) {
            $subjects[$name] = Subject::firstOrCreate(['name' => $name], ['credits' => $credits]);
        }

        // Data mẫu: 12 học sinh, điểm trải đủ 4 mức học lực, chênh lệch giữa các lớp
        $studentsData = [
            ['name' => 'Nguyễn Văn An', 'birth_date' => '2010-03-15', 'gender' => 'male', 'email' => 'an.10a1@student.edu.vn', 'classroom' => '10A1', 'hk1_avg' => 8.3, 'hk2_avg' => 8.6], // Xuất sắc
            ['name' => 'Trần Thị Bình', 'birth_date' => '2010-07-22', 'gender' => 'female', 'email' => 'binh.10a1@student.edu.vn', 'classroom' => '10A1', 'hk1_avg' => 7.9, 'hk2_avg' => 8.3], // Xuất sắc
            ['name' => 'Lê Hoàng Nam', 'birth_date' => '2009-11-05', 'gender' => 'male', 'email' => 'nam.10a1@student.edu.vn', 'classroom' => '10A1', 'hk1_avg' => 6.8, 'hk2_avg' => 7.1], // Giỏi
            ['name' => 'Phạm Thị Hương', 'birth_date' => '2010-01-30', 'gender' => 'female', 'email' => 'huong.10a1@student.edu.vn', 'classroom' => '10A1', 'hk1_avg' => 6.5, 'hk2_avg' => 6.9], // Giỏi

            ['name' => 'Hoàng Văn Đức', 'birth_date' => '2009-05-10', 'gender' => 'male', 'email' => 'duc.11a2@student.edu.vn', 'classroom' => '11A2', 'hk1_avg' => 7.7, 'hk2_avg' => 8.1], // Xuất sắc
            ['name' => 'Vũ Thị Lan', 'birth_date' => '2008-09-18', 'gender' => 'female', 'email' => 'lan.11a2@student.edu.vn', 'classroom' => '11A2', 'hk1_avg' => 7.0, 'hk2_avg' => 7.3], // Giỏi
            ['name' => 'Đặng Minh Khang', 'birth_date' => '2009-02-25', 'gender' => 'male', 'email' => 'khang.11a2@student.edu.vn', 'classroom' => '11A2', 'hk1_avg' => 5.3, 'hk2_avg' => 5.6], // Khá
            ['name' => 'Bùi Thị Ngọc', 'birth_date' => '2008-12-03', 'gender' => 'female', 'email' => 'ngoc.11a2@student.edu.vn', 'classroom' => '11A2', 'hk1_avg' => 5.0, 'hk2_avg' => 5.4], // Khá

            ['name' => 'Đỗ Văn Tùng', 'birth_date' => '2008-04-12', 'gender' => 'male', 'email' => 'tung.12a3@student.edu.vn', 'classroom' => '12A3', 'hk1_avg' => 6.3, 'hk2_avg' => 6.7], // Giỏi
            ['name' => 'Ngô Thị Thảo', 'birth_date' => '2007-10-08', 'gender' => 'female', 'email' => 'thao.12a3@student.edu.vn', 'classroom' => '12A3', 'hk1_avg' => 5.2, 'hk2_avg' => 5.5], // Khá
            ['name' => 'Dương Văn Hải', 'birth_date' => '2008-06-20', 'gender' => 'male', 'email' => 'hai.12a3@student.edu.vn', 'classroom' => '12A3', 'hk1_avg' => 4.3, 'hk2_avg' => 4.6], // Yếu
            ['name' => 'Lý Thị Mai', 'birth_date' => '2007-08-14', 'gender' => 'female', 'email' => 'mai.12a3@student.edu.vn', 'classroom' => '12A3', 'hk1_avg' => 4.2, 'hk2_avg' => 4.4], // Yếu

            // Tài khoản test thật — liên kết Firebase qua email Google
            ['name' => 'Đặng Minh Anh', 'birth_date' => '2009-05-11', 'gender' => 'female', 'email' => 'minhanhdang511@gmail.com', 'classroom' => '10A1', 'hk1_avg' => 7.8, 'hk2_avg' => 8.1], // Giỏi
        ];

        $students = [];
        foreach ($studentsData as $data) {
            $students[] = Student::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'birth_date' => $data['birth_date'],
                    'gender' => $data['gender'],
                    'classroom_id' => $classrooms[$data['classroom']]->id,
                ]
            );
        }

        // Điểm từng môn = mục tiêu học kỳ +/- độ lệch xoay theo môn, tổng độ lệch luôn bằng 0
        $subjectList = array_values($subjects);
        $deviationPattern = [-0.3, 0.2, -0.1, 0.3, -0.1]; // theo thứ tự: Toán, Ngữ Văn, Tiếng Anh, Vật Lý, Hóa Học
        $deviationCount = count($deviationPattern);

        foreach ($students as $index => $student) {
            $rotation = $index % $deviationCount;
            $semesterTargets = [
                'HK1-2025' => $studentsData[$index]['hk1_avg'],
                'HK2-2025' => $studentsData[$index]['hk2_avg'],
            ];

            foreach ($semesterTargets as $semester => $targetAvg) {
                foreach ($subjectList as $subjectIndex => $subject) {
                    $deviation = $deviationPattern[($subjectIndex + $rotation) % $deviationCount];
                    $subjectTarget = round($targetAvg + $deviation, 1);

                    // 4 điểm thành phần lệch quanh target, trung bình theo trọng số ra đúng $subjectTarget
                    Grade::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'subject_id' => $subject->id,
                            'semester' => $semester,
                        ],
                        [
                            'oral_score' => round($subjectTarget + 0.4, 1),
                            'quiz15_score' => round($subjectTarget - 0.4, 1),
                            'test45_score' => round($subjectTarget + 0.3, 1),
                            'final_score' => round($subjectTarget - 0.2, 1),
                        ]
                    );
                }
            }
        }

        // Thời khóa biểu mẫu: vài tiết dạy cho mỗi giáo viên, đủ dữ liệu demo cho trang "Thời khóa biểu".
        $lessonsData = [
            ['teacher@academie.edu.vn', 2, 1, '10A1', 'Toán'],
            ['teacher@academie.edu.vn', 3, 2, '11A2', 'Toán'],
            ['teacher@academie.edu.vn', 5, 1, '12A3', 'Toán'],
            ['lan.toan@academie.edu.vn', 2, 2, '10A1', 'Ngữ Văn'],
            ['lan.toan@academie.edu.vn', 4, 1, '11A2', 'Ngữ Văn'],
            ['binh.van@academie.edu.vn', 3, 1, '10A1', 'Tiếng Anh'],
            ['binh.van@academie.edu.vn', 5, 2, '12A3', 'Tiếng Anh'],
            ['hong.anh@academie.edu.vn', 2, 3, '11A2', 'Hóa Học'],
            ['hong.anh@academie.edu.vn', 6, 1, '12A3', 'Hóa Học'],
        ];

        foreach ($lessonsData as [$teacherEmail, $day, $period, $classroomName, $subjectName]) {
            $teacherUser = User::where('email', $teacherEmail)->first();

            Lesson::firstOrCreate(
                ['day' => $day, 'period' => $period, 'classroom_id' => $classrooms[$classroomName]->id],
                ['subject_id' => $subjects[$subjectName]->id, 'teacher_id' => $teacherUser->id]
            );
        }

        $defaultTeacher = User::where('email', 'teacher@academie.edu.vn')->first();

        $commentsData = [
            [$students[0], 'good', 'Chăm chỉ, tích cực phát biểu trong giờ học.', 60],
            [$students[0], 'good', 'Có tiến bộ rõ rệt, giữ vững phong độ học tập.', 5],
            [$students[4], 'fair', 'Cần cố gắng hơn trong việc hoàn thành bài tập về nhà.', 45],
            [$students[8], 'avg', 'Còn thiếu tập trung trong giờ học, cần nhắc nhở thêm.', 30],
            [$students[12], 'good', 'Học sinh chăm chỉ, hoàn thành tốt bài tập về nhà.', 20],
            [$students[12], 'good', 'Tích cực tham gia các hoạt động nhóm, có tinh thần hợp tác cao.', 7],
        ];

        foreach ($commentsData as [$student, $conduct, $content, $daysAgo]) {
            $exists = Comment::where('student_id', $student->id)->where('content', $content)->exists();

            if (! $exists) {
                $comment = new Comment([
                    'student_id' => $student->id,
                    'teacher_id' => $defaultTeacher->id,
                    'conduct' => $conduct,
                    'content' => $content,
                ]);
                $comment->created_at = now()->subDays($daysAgo);
                $comment->updated_at = $comment->created_at;
                $comment->save();
            }
        }
    }
}
