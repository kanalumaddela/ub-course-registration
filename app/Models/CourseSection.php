<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'section',
        'details',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function catalog()
    {
        return $this->belongsTo(Catalog::class, 'term_id');
    }

    public function schedule()
    {
        return $this->hasMany(CourseSectionSchedule::class);
    }

    public function registrations()
    {
        return $this->hasMany(StudentRegistration::class);
    }
}
