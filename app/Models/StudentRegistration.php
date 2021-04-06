<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRegistration extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'course_section_id',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(User::class);
    }

    public function courseSection()
    {
        return $this->belongsTo(CourseSection::class);
    }
}
