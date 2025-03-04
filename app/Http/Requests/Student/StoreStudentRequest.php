<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_id' => 'required|unique:students,student_id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'status' => 'required|in:active,inactive'
        ];
    }

    public function messages()
    {
        return [
            'student_id.required' => 'Student ID is required',
            'student_id.unique' => 'This Student ID is already taken',
            'name.required' => 'Name is required',
            'name.string' => 'Name must be text',
            'name.max' => 'Name cannot exceed 255 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'status.required' => 'Status is required',
            'status.in' => 'Status must be either active or inactive'
        ];
    }
}