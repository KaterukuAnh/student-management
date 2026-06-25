<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->float('oral_score')->nullable()->after('subject_id');
            $table->float('quiz15_score')->nullable()->after('oral_score');
            $table->float('test45_score')->nullable()->after('quiz15_score');
            $table->float('final_score')->nullable()->after('test45_score');
        });

        // Map điểm cũ sang "final_score" (trọng số cao nhất) để không mất dữ liệu
        DB::statement('UPDATE grades SET final_score = score WHERE score IS NOT NULL');

        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn('score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->float('score')->nullable()->after('subject_id');
        });

        DB::statement('UPDATE grades SET score = final_score WHERE final_score IS NOT NULL');

        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn(['oral_score', 'quiz15_score', 'test45_score', 'final_score']);
        });
    }
};
