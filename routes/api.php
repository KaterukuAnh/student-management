<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\MeController;
use App\Http\Controllers\Api\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/firebase', [AuthController::class, 'firebase']);

Route::middleware(['auth:sanctum', 'role:student'])->group(function () {
    Route::get('/me', [MeController::class, 'show']);
    Route::get('/grades', [GradeController::class, 'index']);
    Route::get('/schedule', [ScheduleController::class, 'index']);
    Route::get('/comments', [CommentController::class, 'index']);
});
