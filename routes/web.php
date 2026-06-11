<?php

use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::resource('classrooms', ClassroomController::class);
Route::resource('students', StudentController::class);