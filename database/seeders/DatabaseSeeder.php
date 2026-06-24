<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Grade;
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

        // 3 lớp học
        $classroomsData = [
            '10A1' => '10',
            '11A2' => '11',
            '12A3' => '12',
        ];

        $classrooms = [];
        foreach ($classroomsData as $name => $grade) {
            $classrooms[$name] = Classroom::firstOrCreate(['name' => $name], ['grade' => $grade]);
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

        // 12 học sinh, chia đều 4 học sinh / lớp
        $studentsData = [
            ['name' => 'Nguyễn Văn An', 'birth_date' => '2010-03-15', 'gender' => 'male', 'email' => 'an.10a1@student.edu.vn', 'classroom' => '10A1'],
            ['name' => 'Trần Thị Bình', 'birth_date' => '2010-07-22', 'gender' => 'female', 'email' => 'binh.10a1@student.edu.vn', 'classroom' => '10A1'],
            ['name' => 'Lê Hoàng Nam', 'birth_date' => '2009-11-05', 'gender' => 'male', 'email' => 'nam.10a1@student.edu.vn', 'classroom' => '10A1'],
            ['name' => 'Phạm Thị Hương', 'birth_date' => '2010-01-30', 'gender' => 'female', 'email' => 'huong.10a1@student.edu.vn', 'classroom' => '10A1'],

            ['name' => 'Hoàng Văn Đức', 'birth_date' => '2009-05-10', 'gender' => 'male', 'email' => 'duc.11a2@student.edu.vn', 'classroom' => '11A2'],
            ['name' => 'Vũ Thị Lan', 'birth_date' => '2008-09-18', 'gender' => 'female', 'email' => 'lan.11a2@student.edu.vn', 'classroom' => '11A2'],
            ['name' => 'Đặng Minh Khang', 'birth_date' => '2009-02-25', 'gender' => 'male', 'email' => 'khang.11a2@student.edu.vn', 'classroom' => '11A2'],
            ['name' => 'Bùi Thị Ngọc', 'birth_date' => '2008-12-03', 'gender' => 'female', 'email' => 'ngoc.11a2@student.edu.vn', 'classroom' => '11A2'],

            ['name' => 'Đỗ Văn Tùng', 'birth_date' => '2008-04-12', 'gender' => 'male', 'email' => 'tung.12a3@student.edu.vn', 'classroom' => '12A3'],
            ['name' => 'Ngô Thị Thảo', 'birth_date' => '2007-10-08', 'gender' => 'female', 'email' => 'thao.12a3@student.edu.vn', 'classroom' => '12A3'],
            ['name' => 'Dương Văn Hải', 'birth_date' => '2008-06-20', 'gender' => 'male', 'email' => 'hai.12a3@student.edu.vn', 'classroom' => '12A3'],
            ['name' => 'Lý Thị Mai', 'birth_date' => '2007-08-14', 'gender' => 'female', 'email' => 'mai.12a3@student.edu.vn', 'classroom' => '12A3'],
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

        // Điểm: mỗi học sinh, mỗi môn, 2 học kỳ, điểm ngẫu nhiên 4.0 - 9.5
        $semesters = ['HK1-2025', 'HK2-2025'];

        foreach ($students as $student) {
            foreach ($subjects as $subject) {
                foreach ($semesters as $semester) {
                    Grade::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'subject_id' => $subject->id,
                            'semester' => $semester,
                        ],
                        [
                            'score' => round(mt_rand(40, 95) / 10, 1),
                        ]
                    );
                }
            }
        }
    }
}
