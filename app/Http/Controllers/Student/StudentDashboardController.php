<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Models\Student\Students;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $student = Students::with(['subjects', 'grades'])
            ->where('email', Auth::user()->email)
            ->first();

        if (!$student) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Student record not found. Please contact the administrator.',
            ]);
        }

        $enrolledSubjects = $student->subjects;
        return view('Students.StudentDashboard', compact('enrolledSubjects'));
    }

    public function subjects()
    {
        $student = Students::with('subjects')
            ->where('email', Auth::user()->email)
            ->first();

        return view('Students.studentSubjects', compact('student'));
    }

    public function grades()
    {
        $student = Students::with(['subjects', 'grades'])
            ->where('email', Auth::user()->email)
            ->first();

        return view('Students.studentGrades', compact('student'));
    }
}