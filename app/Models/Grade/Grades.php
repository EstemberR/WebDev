<?php

namespace App\Models\Grade;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student\Students;
use App\Models\Subject\Subjects;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grades extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'subject_name',
        'subject_code',
        'midterm',
        'finals',
        'average',
        'remarks'
    ];

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subjects::class, 'subject_id');
    }
}