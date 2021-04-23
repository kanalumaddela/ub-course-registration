<?php

namespace App\Http\Controllers;


use App\Models\Course;
use Inertia\Inertia;

class CourseController
{
    public function index()
    {
        /**
         * @var \Illuminate\Pagination\Paginator $catalogs
         */
        $catalogs = Course::paginate(20);


        return Inertia::render('Courses', get_defined_vars());
    }

    public function view(Course $course)
    {
        return $course;
    }

    public function search()
    {

    }
}
