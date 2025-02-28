<?php

namespace App\Http\Controllers;

use App\Models\Student\Students;
use App\Models\Subject\Subjects;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index()
    {
        return view('enrollment', [
            'students' => Students::whereDoesntHave('subjects')->get(),
            'enrolledStudents' => Students::has('subjects')->with('subjects')->get(),
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
            $student->subjects()->sync($request->subjects);
            
            return response()->json([
                'success' => true,
                'message' => 'Subjects updated successfully'
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
            
            // Delete related grades first
            $student->grades()->delete();
            
            // Detach all subjects
            $student->subjects()->detach();

            return response()->json([
                'success' => true,
                'message' => 'Student has been unenrolled successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error unenrolling student'
            ], 422);
        }
    }
}