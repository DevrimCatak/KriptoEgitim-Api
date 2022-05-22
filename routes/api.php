<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\LessonController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('courses', [CourseController::class, 'courses']);
    Route::get('lessons/{id}', [LessonController::class, 'lessons']);
    Route::get('lesson/{id}', [LessonController::class, 'lesson']);
    Route::get('complete_lesson/{id}', [LessonController::class, 'completedLesson']);
    Route::get('user', [UserController::class, 'user']);
    Route::post('user', [UserController::class, 'user_update']);
    Route::post('change_password', [UserController::class, 'change_password']);
});

