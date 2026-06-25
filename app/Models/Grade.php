<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'student_id', 'subject_id', 'semester',
        'oral_score', 'quiz15_score', 'test45_score', 'final_score',
    ];

    // Hệ số tính điểm trung bình môn theo quy chế phổ biến: miệng x1, 15 phút x1, 45 phút x2, cuối kỳ x3.
    public const WEIGHTS = [
        'oral_score' => 1,
        'quiz15_score' => 1,
        'test45_score' => 2,
        'final_score' => 3,
    ];

    protected function casts(): array
    {
        return [
            'oral_score' => 'float',
            'quiz15_score' => 'float',
            'test45_score' => 'float',
            'final_score' => 'float',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Điểm trung bình môn — tính theo trọng số, chỉ trên các cột đã có điểm (không bắt buộc nhập đủ 4 cột).
    protected function score(): Attribute
    {
        return Attribute::make(
            get: function () {
                $weightedSum = 0;
                $totalWeight = 0;

                foreach (self::WEIGHTS as $column => $weight) {
                    if ($this->{$column} !== null) {
                        $weightedSum += $this->{$column} * $weight;
                        $totalWeight += $weight;
                    }
                }

                return $totalWeight > 0 ? round($weightedSum / $totalWeight, 2) : null;
            },
        );
    }
}
