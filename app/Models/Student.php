<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name', 'birth_date', 'gender', 'email', 'classroom_id'
    ];

    // Quan hệ: 1 học sinh thuộc 1 lớp
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    // Quan hệ: 1 học sinh có nhiều điểm
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
