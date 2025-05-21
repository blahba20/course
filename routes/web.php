<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'register'])->middleware('guest');
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth');

// Student routes
Route::prefix('student')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->role != 'student') {
            return redirect('/instructor/dashboard');
        }
        return view('student.dashboard');
    })->name('student.dashboard');
});

// Instructor routes
Route::prefix('instructor')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->role != 'instructor') {
            return redirect('/student/dashboard');
        }
        return view('instructor.dashboard');
    })->name('instructor.dashboard');
});

// Course routes
Route::middleware(['auth'])->group(function () {
    Route::get('courses', [CourseController::class, 'index'])->name('courses.index');
    
    // Instructor-only routes
    Route::middleware(['role:instructor'])->group(function () {
        Route::get('courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('courses', [CourseController::class, 'store'])->name('courses.store');
    });
    
    Route::get('courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    
    // Instructor-only routes for existing courses
    Route::middleware(['role:instructor'])->group(function () {
        Route::get('courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    });
});

// Enrollment routes
Route::resource('enrollments', EnrollmentController::class)->except(['create', 'edit']);
Route::get('/courses/{course}/students', [EnrollmentController::class, 'students'])
    ->name('courses.students')
    ->middleware('auth');
Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll'])
    ->name('courses.enroll')
    ->middleware(['auth', 'role:student']);
Route::post('/courses/{course}/unenroll', [EnrollmentController::class, 'unenroll'])
    ->name('courses.unenroll')
    ->middleware(['auth', 'role:student']);

// Instructor courses route
Route::get('/instructor/my-courses', [CourseController::class, 'myCourses'])
    ->name('instructor.courses')
    ->middleware(['auth', 'role:instructor']);

// Export routes
Route::get('/export/student/enrollments/json', [ExportController::class, 'studentEnrollmentsJson'])
    ->name('export.student.enrollments.json')
    ->middleware(['auth', 'role:student']);

Route::get('/export/student/enrollments/xml', [ExportController::class, 'studentEnrollmentsXml'])
    ->name('export.student.enrollments.xml')
    ->middleware(['auth', 'role:student']);

Route::get('/export/instructor/courses/json', [ExportController::class, 'instructorCoursesJson'])
    ->name('export.instructor.courses.json')
    ->middleware(['auth', 'role:instructor']);

Route::get('/export/instructor/courses/xml', [ExportController::class, 'instructorCoursesXml'])
    ->name('export.instructor.courses.xml')
    ->middleware(['auth', 'role:instructor']);
