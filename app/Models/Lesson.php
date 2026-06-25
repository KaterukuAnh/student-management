<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['day', 'period', 'classroom_id', 'subject_id', 'teacher_id'];

    public const DAYS = [
        2 => 'Thứ 2',
        3 => 'Thứ 3',
        4 => 'Thứ 4',
        5 => 'Thứ 5',
        6 => 'Thứ 6',
        7 => 'Thứ 7',
    ];

    public const PERIOD_TIMES = [
        1 => '07:15',
        2 => '08:05',
        3 => '09:10',
        4 => '10:00',
        5 => '10:50',
    ];

    // Quan hệ: 1 tiết học thuộc 1 lớp
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    // Quan hệ: 1 tiết học thuộc 1 môn
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Quan hệ: 1 tiết học do 1 giáo viên dạy
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
