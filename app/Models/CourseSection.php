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

    public function schedule()
    {
        return $this->hasMany(CourseSectionSchedule::class);
    }
}
