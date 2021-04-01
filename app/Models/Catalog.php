<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Catalog extends Model
{
    use HasFactory;

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function scopeActive(Builder $query)
    {
        // todo: determine for current year
    }
}
