<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'prefix',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function advisors()
    {
        return $this->belongsToMany(User::class, 'department_advisors');
    }
}
