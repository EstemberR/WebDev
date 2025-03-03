<?php

namespace App\Http\Requests\Enrollment;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'subjects' => 'required|array',
            'subjects.*' => 'exists:subjects,id'
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'Student ID is required',
            'student_id.exists' => 'Selected student does not exist',
            'subjects.required' => 'Please select at least one subject',
            'subjects.array' => 'Invalid subjects format',
            'subjects.*.exists' => 'One or more selected subjects do not exist'
        ];
    }
}