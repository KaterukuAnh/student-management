<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['student_id', 'teacher_id', 'conduct', 'content'];

    public const CONDUCTS = [
        'good' => 'Tốt',
        'fair' => 'Khá',
        'avg' => 'Trung bình',
    ];

    // Quan hệ: 1 nhận xét thuộc 1 học sinh
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Quan hệ: 1 nhận xét do 1 giáo viên viết
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
