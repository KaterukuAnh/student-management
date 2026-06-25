<?php

use App\Http\Controllers\AdminLessonController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/lang/{locale}', function (string $locale) {
    abort_unless(in_array($locale, config('app.available_locales'), true), 404);

    session(['locale' => $locale]);

    return redirect()->back();
})->name('lang.switch');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('grades', GradeController::class)->except(['create', 'edit', 'show']);
    Route::get('/grades/entry', [GradeController::class, 'entry'])->name('grades.entry');
    Route::post('/grades/entry', [GradeController::class, 'bulkStore'])->name('grades.entry.store');

    Route::middleware('role:teacher')->group(function () {
        Route::get('/schedule', [LessonController::class, 'index'])->name('schedule');

        Route::get('/comments', [CommentController::class, 'index'])->name('comments');
        Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    });

    Route::middleware('role:admin')->group(function () {
        Route::resource('classrooms', ClassroomController::class)->except(['create', 'edit', 'show']);
        Route::resource('students', StudentController::class)->except(['create', 'edit', 'show']);
        Route::resource('subjects', SubjectController::class)->except(['create', 'edit', 'show']);
        Route::resource('users', UserController::class)->except('show');

        Route::get('/admin/schedule', [AdminLessonController::class, 'index'])->name('admin.schedule');
        Route::post('/admin/schedule', [AdminLessonController::class, 'store'])->name('admin.schedule.store');
        Route::put('/admin/schedule/{lesson}', [AdminLessonController::class, 'update'])->name('admin.schedule.update');
        Route::delete('/admin/schedule/{lesson}', [AdminLessonController::class, 'destroy'])->name('admin.schedule.destroy');
    });
});

require __DIR__.'/auth.php';
