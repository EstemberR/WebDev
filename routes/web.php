<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Subject\SubjectController;
use App\Http\Controllers\Enrollment\EnrollmentController;
use App\Http\Controllers\Grade\GradeController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/profile/exactProfile',[ProfileController::class, 'index'])->name('exactProfile');

Route::middleware(['auth', 'verified'])->group(function () {
    // Base dashboard route that will redirect based on user type
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Add these profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Prevent direct access to layout files
Route::get('/layouts/{any}', function () {
    return redirect('/dashboardTemp');
})->where('any', '.*');

    Route::resource('subjects', SubjectController::class);

Route::middleware(['auth', 'user.type:student'])->group(function () {
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])
        ->name('student.dashboard');
    Route::get('/student/studentSubjects', [StudentDashboardController::class, 'subjects'])
        ->name('student.studentSubjects');
    Route::get('/student/studentGrades', [StudentDashboardController::class, 'grades'])
        ->name('student.studentGrades');
});

Route::middleware(['auth', 'user.type:instructor'])->group(function () {
    Route::resource('students', StudentController::class);
    Route::resource('subjects', SubjectController::class);
    
    // Grade routes
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::post('/grades/store', [GradeController::class, 'store'])->name('grades.store');
    Route::put('/grades/{grade}', [GradeController::class, 'update'])->name('grades.update');
    Route::delete('/grades/{grade}', [GradeController::class, 'destroy'])->name('grades.destroy');
    
    // Enrollment routes
    Route::get('/enrollment', [EnrollmentController::class, 'index'])->name('enrollment.index');
    Route::post('/enrollment/enroll', [EnrollmentController::class, 'enroll'])->name('enrollment.enroll');
    Route::post('/enrollment/subjects', [EnrollmentController::class, 'updateSubjects'])->name('enrollment.subjects');
    Route::get('/api/student/{id}/subjects', [EnrollmentController::class, 'getStudentSubjects']);
    Route::delete('/enrollment/unenroll/{student}', [EnrollmentController::class, 'unenroll'])->name('enrollment.unenroll');
});

Route::delete('/grades/delete/{student}/{subject}', [GradeController::class, 'destroy'])->name('grades.destroy');

Route::get('/grades/subjects/{student_id}', [GradeController::class, 'getSubjects'])->name('grades.subjects');

require __DIR__.'/auth.php';
