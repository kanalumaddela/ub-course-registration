<?php


namespace App\Http\Controllers\Advisor;


use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class RegistrationController
{
    public function index(Request $request)
    {
        $departments = Department::select('departments.*')
            ->join('courses', 'courses.department_id', '=', 'departments.id')
            ->join('course_sections', 'course_sections.course_id', '=', 'courses.id')
            ->join('student_registrations', 'student_registrations.course_section_id', '=', 'course_sections.id')
            ->whereNotIn('student_registrations.status', ['planned', 'approved', 'registered']);

        if (!Gate::check('super')) {
            $departments->join('department_advisors', 'department_advisors.department_id', '=', 'departments.id')
                ->where('department_advisors.user_id', auth()->id());
        }

        $departments = $departments->get();

        dd($departments);

        return Inertia::render('Advisor/Registrations');
    }
}
