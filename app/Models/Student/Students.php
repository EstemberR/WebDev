<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subject\Subjects;
use App\Models\Grade\Grades;

class Students extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'status',
        'password'
    ];

    // Relationship with subjects (many-to-many)
    public function subjects()
    {
        return $this->belongsToMany(Subjects::class, 'student_subject', 'student_id', 'subject_id');
    }

    public function grades()
    {
        return $this->hasMany(Grades::class, 'student_id');
    }
}