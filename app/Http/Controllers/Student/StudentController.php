<?php

namespace App\Http\Controllers\Student;

use App\Models\Student\Students;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    public function index()
    {
        $students = Students::all();
        return view('Students.Students', compact('students'));
    }

    public function store(StoreStudentRequest $request)
    {
        try {
            Students::create($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Student added successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding student: ' . $e->getMessage()
            ], 422);
        }
    }

    public function show(Students $student)
    {
        return view('students.show', compact('student'));
    }

    public function edit(Students $student)
    {
        return redirect()->route('students.index');
    }

    public function update(Request $request, Students $student)
    {
        try {
            $validated = $request->validate([
                'student_id' => 'required|unique:students,student_id,' . $student->id,
                'name' => 'required',
                'email' => [
                    'required',
                    'email',
                    'unique:students,email,' . $student->id,
                    function ($attribute, $value, $fail) {
                        $domain = substr(strrchr($value, "@"), 1);
                        if ($domain !== 'student.buksu.edu.ph') {
                            $fail('The email must be a valid BukSU student email address (@student.buksu.edu.ph).');
                        }
                    },
                ],
                'status' => 'required|in:active,inactive'
            ]);

            $student->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating student. ' . $e->getMessage()
            ], 422);
        }
    }

    public function destroy(Students $student)
    {
        try {
            // First delete related records
            $student->grades()->delete();  // Delete all grades
            $student->subjects()->detach(); // Remove subject relationships
            
            // Delete the student record from users table if exists
            $user = User::where('email', $student->email)->first();
            if ($user) {
                $user->delete();
            }
            
            // Finally delete the student
            $student->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Student and all related records deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting student: ' . $e->getMessage()
            ], 422);
        }
    }
}