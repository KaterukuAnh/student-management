<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['name', 'grade', 'room', 'homeroom_teacher_id'];

    // Quan hệ: 1 lớp có nhiều học sinh
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    // Quan hệ: 1 lớp có 1 giáo viên chủ nhiệm (có thể chưa phân công)
    public function homeroomTeacher()
    {
        return $this->belongsTo(User::class, 'homeroom_teacher_id');
    }
}
