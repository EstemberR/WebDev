<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|unique:students',
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'unique:students',
                function ($attribute, $value, $fail) {
                    $domain = substr(strrchr($value, "@"), 1);
                    if ($domain !== 'student.buksu.edu.ph') {
                        $fail('The email must be a valid BukSU student email address (@student.buksu.edu.ph).');
                    }
                },
            ],
            'status' => 'required|in:active,inactive'
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'Student ID is required',
            'student_id.unique' => 'This Student ID is already taken',
            'name.required' => 'Name is required',
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email address is already registered',
            'status.required' => 'Status is required',
            'status.in' => 'Status must be either active or inactive'
        ];
    }
}