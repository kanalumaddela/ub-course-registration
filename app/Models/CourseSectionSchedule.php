<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSectionSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_section_id',
        'type',
        'start_time',
        'end_time',
        'days',
        'is_online',
        'building_id',
        'room',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
