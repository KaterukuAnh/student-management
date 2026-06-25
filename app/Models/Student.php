<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name', 'birth_date', 'gender', 'email', 'classroom_id',
    ];

    public const GENDERS = [
        'male' => 'Nam',
        'female' => 'Nữ',
        'non_binary' => 'Phi nhị nguyên',
        'genderfluid' => 'Genderfluid',
        'agender' => 'Agender',
        'bigender' => 'Bigender',
        'demiboy' => 'Demiboy',
        'demigirl' => 'Demigirl',
        'trans_male' => 'Chuyển giới nam',
        'trans_female' => 'Chuyển giới nữ',
        'genderqueer' => 'Genderqueer',
        'androgynous' => 'Androgynous',
        'neutrois' => 'Neutrois',
        'two_spirit' => 'Two-Spirit',
        'xenogender' => 'Xenogender',
        'catgender' => 'Catgender',
        'stargender' => 'Stargender',
        'cloudgender' => 'Cloudgender',
        'voidgender' => 'Voidgender',
        'other' => 'Khác',
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

    // Quan hệ: 1 học sinh có nhiều nhận xét
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function genderLabel(): string
    {
        return __(self::GENDERS[$this->gender] ?? $this->gender);
    }
}
