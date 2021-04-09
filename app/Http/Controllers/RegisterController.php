<?php

namespace App\Http\Controllers;


use App\Models\CourseSection;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class RegisterController extends Controller
{
    public function register(CourseSection $courseSection, Request $request)
    {
        $this->authorize('register-for-classes');


//        return \response()->json(['success' => $request->get('success', false), 'message' => $request->get('success', false) ? 'success message' : 'failed message']);

//        return $courseSection;

        $user = Auth::user();

        $success = false;
        $message = null;
        try {

            if ($request->get('type', 'register') === 'register') {
                $user->registrations()->create([
                    'user_id' => $user->id,
                    'course_section_id' => $courseSection->id,
                    'status'            => 'pending',
                ]);
            }
            elseif ($request->get('type') === 'unregister') {
                StudentRegistration::where([
                    ['user_id', '=', $user->id],
                    ['course_section_id', '=', $courseSection->id],
                ])->delete();
            }

            $success = true;
        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return \response()->json(['success' => $success, 'message' => $success ? 'Registered for section' : (env('APP_DEBUG') ? $message : 'Failed to register for section')]);
    }
}
