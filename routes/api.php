<?php

use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\EnrollmentController;
use App\Http\Controllers\API\ExportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Course API routes
Route::apiResource('courses', CourseController::class)->names([
    'index' => 'api.courses.index',
    'store' => 'api.courses.store',
    'show' => 'api.courses.show',
    'update' => 'api.courses.update', 
    'destroy' => 'api.courses.destroy'
]);

// Enrollment API routes
Route::apiResource('enrollments', EnrollmentController::class)->names([
    'index' => 'api.enrollments.index',
    'store' => 'api.enrollments.store',
    'show' => 'api.enrollments.show',
    'update' => 'api.enrollments.update',
    'destroy' => 'api.enrollments.destroy'
]);

// Get students enrolled in a course
Route::get('/courses/{course}/students', [EnrollmentController::class, 'courseStudents'])
    ->middleware('auth:api')
    ->name('api.courses.students');

// Export API routes
Route::get('/export/student/enrollments/json', [ExportController::class, 'studentEnrollmentsJson'])
    ->middleware(['auth:api'])
    ->name('api.export.student.enrollments.json');

Route::get('/export/student/enrollments/xml', [ExportController::class, 'studentEnrollmentsXml'])
    ->middleware(['auth:api'])
    ->name('api.export.student.enrollments.xml');

Route::get('/export/instructor/courses/json', [ExportController::class, 'instructorCoursesJson'])
    ->middleware(['auth:api'])
    ->name('api.export.instructor.courses.json');

Route::get('/export/instructor/courses/xml', [ExportController::class, 'instructorCoursesXml'])
    ->middleware(['auth:api'])
    ->name('api.export.instructor.courses.xml'); 