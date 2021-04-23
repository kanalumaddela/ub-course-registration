<?php

namespace App\Http\Controllers;


use App\Models\CourseSection;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function register(CourseSection $courseSection, Request $request)
    {
        $this->authorize('register-for-classes');

        $user = Auth::user();

        $success = false;
        $message = null;
        try {

            if ($request->get('type', 'register') === 'register') {
                $user->registrations()->create([
                    'user_id'           => $user->id,
                    'course_section_id' => $courseSection->id,
                    'status'            => 'planned',
                ]);
            } elseif ($request->get('type') === 'unregister') {
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

    public function update(StudentRegistration $studentRegistration, Request $request)
    {
        // planned -> register
        // pending -> cancel
        // approved -> drop
        // denied -> remove

        $request->validate([
            'action' => 'required|in:register,cancel',
        ]);

        $action = $request->input('action');

        switch ($action) {
            case 'cancel':
                $studentRegistration->update([
                    'status' => 'planned',
                ]);
                break;
            case 'register':
                if ($studentRegistration->status === 'planned') {
                    $studentRegistration->update([
                        'status' => 'pending',
                    ]);
                } else {
                    $studentRegistration->update([
                        'status' => 'registered',
                    ]);
                }
                break;
        }

        return redirect()->action([IndexController::class, 'index']);
    }

    public function delete(StudentRegistration $studentRegistration, Request $request)
    {

        $studentRegistration->delete();

        return redirect()->action([IndexController::class, 'index']);
    }

    public function registerAll()
    {
        foreach (StudentRegistration::whereIn('status', ['planned', 'approved'])->where('user_id', \auth()->id())->get() as $registration) {
            $registration->status = $registration->status === 'planned' ? 'pending' : 'registered';
            $registration->save();
        }

        return redirect()->route('index');
    }
}
