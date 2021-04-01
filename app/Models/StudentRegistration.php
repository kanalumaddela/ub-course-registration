<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRegistration extends Model
{
    use HasFactory;

    public function student()
    {
        return $this->belongsTo(User::class);
    }

    public function courseSection()
    {
        return $this->belongsTo(CourseSection::class);
    }
}
