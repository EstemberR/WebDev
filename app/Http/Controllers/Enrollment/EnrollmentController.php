<?php

namespace App\Http\Controllers\Enrollment;

use App\Models\Student\Students;
use App\Models\Subject\Subjects;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EnrollmentController extends Controller
{
    public function index()
    {
        return view('enrollment.AvailableStudents', [
            'students' => Students::whereDoesntHave('subjects')->whereDoesntHave('grades')->get(),
            'subjects' => Subjects::all(),
        ]);
    }

    public function enrolled()
    {
        return view('enrollment.enrolledStudents', [
            'enrolledStudents' => Students::where(function($query) {
                $query->has('subjects')->orHas('grades');
            })->with(['subjects', 'grades' => function($query) {
                $query->whereNull('subject_id');
            }])->get(),
            'subjects' => Subjects::all(),
        ]);
    }

    public function enroll(Request $request)
    {
        try {
            $validated = $request->validate([
                'student_id' => 'required|exists:students,id',
                'subjects' => 'required|array',
                'subjects.*' => 'exists:subjects,id'
            ]);

            $student = Students::findOrFail($validated['student_id']);
            
            // Check for existing subjects and only attach new ones
            $existingSubjects = $student->subjects->pluck('id')->toArray();
            $newSubjects = array_diff($validated['subjects'], $existingSubjects);
            
            if (empty($newSubjects)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student is already enrolled in these subjects'
                ], 422);
            }

            $student->subjects()->attach($newSubjects);

            return response()->json([
                'success' => true,
                'message' => 'Student enrolled successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error enrolling student'
            ], 422);
        }
    }

    public function updateSubjects(Request $request)
    {
        try {
            $student = Students::findOrFail($request->student_id);
            $student->subjects()->sync($request->subjects ?? []);
            
            return response()->json([
                'success' => true,
                'message' => 'Subjects updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating subjects: ' . $e->getMessage()
            ], 422);
        }
    }

    public function getStudentSubjects($id)
    {
        $student = Students::findOrFail($id);
        return response()->json($student->subjects->pluck('id'));
    }

    public function unenroll($studentId)
    {
        try {
            $student = Students::findOrFail($studentId);
            
            // Delete all grades for the student
            $student->grades()->delete();
            
            // Detach all subjects
            $student->subjects()->detach();

            return response()->json([
                'success' => true,
                'message' => 'Student has been unenrolled successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error unenrolling student: ' . $e->getMessage()
            ], 422);
        }
    }

    public function getSubjects($studentId)
    {
        $student = Students::findOrFail($studentId);
        return response()->json([
            'subjects' => $student->subjects->pluck('id')
        ]);
    }
}