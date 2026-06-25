<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('day')->comment('Thứ trong tuần: 2 = Thứ 2 ... 7 = Thứ 7');
            $table->unsignedTinyInteger('period')->comment('Tiết học trong ngày: 1-5');
            $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            // Một lớp chỉ có 1 tiết học mỗi khung giờ; một giáo viên chỉ dạy 1 nơi mỗi khung giờ.
            $table->unique(['day', 'period', 'classroom_id']);
            $table->unique(['day', 'period', 'teacher_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
