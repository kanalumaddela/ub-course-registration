<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSectionSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_section_id',
        'days',
        'start_time',
        'duration',
        'location',
    ];

    protected $casts = [
        'days' => 'array'
    ];
}
