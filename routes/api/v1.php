<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Admin\CourseController;
use App\Http\Controllers\Api\V1\Admin\IntakeController;
use App\Http\Controllers\Api\V1\Admin\LecturerManagementController;
use App\Http\Controllers\Api\V1\Admin\ModuleController;
use App\Http\Controllers\Api\V1\Admin\SchoolManagementController;
use App\Http\Controllers\Api\V1\Admin\StudentManagementController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Lecturer\LecturerController;
use App\Http\Controllers\Api\V1\Shared\ProfileController;
use Illuminate\Support\Facades\Route;

/**
 * auth routes
 */
Route::prefix('auth')->as('auth:')->group(function () {
    Route::post('login', LoginController::class)->name('login');
});

/**
 * routes to be accessed by admin
 */
Route::group([
    'prefix' => 'admin',
    'as' => 'admin:',
    'middleware' => ['auth:sanctum', 'abilities:admin']
], function () {
    Route::apiResource('students', StudentManagementController::class)
        ->except(['edit', 'create', 'show']);

    Route::apiResource('lecturers', LecturerManagementController::class)
     ->except(['edit', 'create', 'show']);

    Route::apiResource('intakes', IntakeController::class)
        ->except(['edit', 'create', 'show']);

    Route::apiResource('courses', CourseController::class)
        ->except(['edit', 'create', 'show']);

    Route::apiResource('modules', ModuleController::class)
        ->except(['edit', 'create', 'show']);

    Route::post('/school/intakes/{intake}/courses/{course}/link', [SchoolManagementController::class, 'linkIntakeToCourse']);
    Route::post('/school/courses/{course}/modules/{module}/link', [SchoolManagementController::class, 'linkCourseToModule']);
});

/**
 * routes for lectures only
 */

Route::group([
    'prefix' => 'lecturers',
    'as' => 'lecturers:',
    'middleware' => ['auth:sanctum', 'abilities:lecturer']
], function () {
    Route::controller(LecturerController::class)->group(function () {
        Route::get("{lecturer}/intakes", 'getIntakes');
        Route::get("{lecturer}/attendances", 'getAttendances');
        Route::post("{lecturer}/attendances", 'createAttendance');
        Route::get("/students", 'getStudentCards');
        Route::post("/attendances/{attendance}/students/register", 'registerScannedStudent');
        Route::delete("/attendances/{attendance}", 'deleteAttendance');
    });
});

/**
 * routes shared by all authenticated users
 */

Route::group([
    'prefix' => 'users',
    'as' => 'users:',
    'middleware' => ['auth:sanctum']
], function () {
    Route::controller(ProfileController::class)->group(function () {
        Route::get('lecturers/{lecturer}/profile', 'getLecturerProfile');
        Route::get('/admin/{admin}/profile', 'getAdminProfile');
        Route::get('/students/{student}/profile', 'getStudentProfile');
        Route::post('/{user}/reset-password', 'updatePassword');
        Route::post('/{user}/upload-profile-pic', 'uploadProfilePicture');
    });
});
