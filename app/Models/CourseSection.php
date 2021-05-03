<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'seats',
        'start_date',
        'end_date',
        'faculty',
        'course_id',
        'catalog_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
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
