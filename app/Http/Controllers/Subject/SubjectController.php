<?php

namespace App\Http\Controllers\Subject;

use App\Models\Subject\Students;
use App\Models\Subject\Subjects;
use Illuminate\Http\Request;
use App\Http\Requests\Subject\StoreSubject;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subjects::all();
        return view('Subjects.Subjects', compact('subjects'));
    }

    public function store(StoreSubject $request)
    {
        try {
            Subjects::create($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Subject added successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Subject already exists.'
            ], 422);
        }
    }

    public function show(Subjects $subject)
    {
        return view('Subjects.show', compact('subject'));
    }

    public function edit(Subjects $subject)
    {
        return view('Subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subjects $subject)
    {
        try {
            $validated = $request->validate([
                'subject_code' => 'required|unique:subjects,subject_code,' . $subject->id,
                'name' => 'required',
                'description' => 'nullable',
                'units' => 'required|integer',
                'schedule' => 'nullable'
            ]);

            $subject->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Subject updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating subject. ' . $e->getMessage()
            ], 422);
        }
    }

    public function destroy(Subjects $subject)
    {
        try {
            DB::beginTransaction();
            
            // Store subject information in grades before deletion
            foreach ($subject->grades as $grade) {
                $grade->update([
                    'subject_name' => $subject->name,
                    'subject_code' => $subject->subject_code,
                    'subject_id' => null  // Set subject_id to null since we're deleting the subject
                ]);
            }
            
            // Remove subject relationships without deleting grades
            $subject->students()->detach();
            
            // Delete the subject
            $subject->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Subject deleted successfully! All grades have been preserved.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting subject: ' . $e->getMessage()
            ], 422);
        }
    }
}