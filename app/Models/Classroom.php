<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['name', 'grade'];

    // Quan hệ: 1 lớp có nhiều học sinh
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
