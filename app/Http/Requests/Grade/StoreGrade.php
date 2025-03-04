<?php

namespace App\Http\Requests\Grade;

use Illuminate\Foundation\Http\FormRequest;

class StoreGrade extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'midterm' => 'required|numeric|min:1|max:5',
            'finals' => 'required|numeric|min:1|max:5'
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'Student ID is required',
            'student_id.exists' => 'Selected student does not exist',
            'subject_id.required' => 'Subject ID is required',
            'subject_id.exists' => 'Selected subject does not exist',
            'midterm.required' => 'Midterm grade is required',
            'midterm.numeric' => 'Midterm grade must be a number',
            'midterm.min' => 'Minimum grade is 1.0',
            'midterm.max' => 'Maximum grade is 5.0',
            'finals.required' => 'Final grade is required',
            'finals.numeric' => 'Final grade must be a number',
            'finals.min' => 'Minimum grade is 1.0',
            'finals.max' => 'Maximum grade is 5.0'
        ];
    }
}