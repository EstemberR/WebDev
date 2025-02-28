<?php

namespace App\Http\Controllers;

use App\Models\Grade\Grades;
use App\Models\Student\Students;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $students = Students::has('subjects')->with(['subjects', 'grades'])->get();
        return view('Grade.Grade', compact('students'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'student_id' => 'required|exists:students,id',
                'subject_id' => 'required|exists:subjects,id',
                'midterm' => 'required|numeric|min:1|max:5',
                'finals' => 'required|numeric|min:1|max:5'
            ]);

            // Calculate average of grade points directly
            $average = ($validated['midterm'] + $validated['finals']) / 2;
            
            // Round to 2 decimal places
            $average = round($average, 2);
            
            // Determine remarks
            $remarks = $average <= 3.00 ? 'Passed' : 'Failed';

            Grades::updateOrCreate(
                [
                    'student_id' => $validated['student_id'],
                    'subject_id' => $validated['subject_id']
                ],
                [
                    'midterm' => $validated['midterm'],
                    'finals' => $validated['finals'],
                    'average' => $average,
                    'remarks' => $remarks
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Grades saved successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving grades: ' . $e->getMessage()
            ], 422);
        }
    }

    private function convertToGradePoint($percentage)
    {
        if ($percentage >= 97) return 1.00;
        if ($percentage >= 94) return 1.25;
        if ($percentage >= 91) return 1.50;
        if ($percentage >= 88) return 1.75;
        if ($percentage >= 85) return 2.00;
        if ($percentage >= 82) return 2.25;
        if ($percentage >= 79) return 2.50;
        if ($percentage >= 76) return 2.75;
        if ($percentage >= 75) return 3.00;
        return 5.00;
    }

    public function destroy($studentId, $subjectId)
    {
        try {
            $grade = Grades::where('student_id', $studentId)
                         ->where('subject_id', $subjectId)
                         ->first();

            if (!$grade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Grade not found'
                ]);
            }

            $grade->delete();

            return response()->json([
                'success' => true,
                'message' => 'Grade deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting grade'
            ]);
        }
    }
}