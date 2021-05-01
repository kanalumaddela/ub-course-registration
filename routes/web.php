<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin'       => Route::has('login'),
//        'canRegister'    => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion'     => PHP_VERSION,
//    ]);
//});


Route::get('/dashboard', function () {
    return redirect()->route('index');
})->name('dashboard');

//Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//
//
//    return Inertia::render('Dashboard');
//})->name('dashboard');

// new dashboard
Route::get('/', [\App\Http\Controllers\IndexController::class, 'index'])->name('index');

Route::middleware(['auth:sanctum', 'roleCustom:admin|student'])->group(function () {
    Route::post('/register-all', [\App\Http\Controllers\RegisterController::class, 'registerAll'])->name('studentRegistration.registerAll');
    Route::post('/update-registration/{studentRegistration}', [\App\Http\Controllers\RegisterController::class, 'update'])->name('studentRegistration.update');
    Route::delete('/update-registration/{studentRegistration}', [\App\Http\Controllers\RegisterController::class, 'delete'])->name('studentRegistration.delete');
});

// notifications
Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
Route::get('/notification/{notification}', function (\Illuminate\Notifications\DatabaseNotification $notification) {
    if ($notification->unread()) {
        $notification->markAsRead();
    }

    if (isset($notification->data['url']) && !empty($notification->data['url'])) {
        return redirect($notification->data['url']);
    }

    return redirect()->back();
})->name('notifications.view');
Route::post('/notifications/clear-all', [\App\Http\Controllers\NotificationController::class, 'clearAll'])->name('notifications.clearAll');
Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');

// all courses
Route::get('/search', \App\Http\Controllers\SearchController::class)->name('search');

// register for course section
Route::post('/register-section/{courseSection}', [\App\Http\Controllers\RegisterController::class, 'register'])
    ->middleware(['auth:sanctum', 'roleCustom:admin|student'])
    ->name('register.section');

// courses
Route::get('/courses', [\App\Http\Controllers\CourseController::class, 'index'])->name('courses.index');
Route::get('/course/{course}', [\App\Http\Controllers\CourseController::class, 'view'])->name('courses.view');

// user
Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
Route::get('/user/{user}', [\App\Http\Controllers\UserController::class, 'view'])->name('users.view');

// advisor
Route::group(['prefix' => '/dashboard/advisor', 'as' => 'advisor.', 'middleware' => ['roleCustom:admin|advisor']], function () {
    Route::get('/', [\App\Http\Controllers\Advisor\RegistrationController::class, 'index'])->name('registrations');
    Route::get('/student-schedule/{student}', [\App\Http\Controllers\Advisor\RegistrationController::class, 'studentSchedule'])->name('students.schedule');
    Route::post('/registration/{studentRegistration}', [\App\Http\Controllers\Advisor\RegistrationController::class, 'update'])->name('registrations.update');
    Route::get('/registration/{student}', [\App\Http\Controllers\Advisor\RegistrationController::class, 'index'])->name('registrations.view');
});


// admin
Route::group(['prefix' => '/admin', 'as' => 'admin.', 'middleware' => ['roleCustom:admin']], function () {
    Route::get('/', [\App\Http\Controllers\Admin\IndexController::class, 'index'])->name('index');
    Route::get('/advisors', [\App\Http\Controllers\Admin\IndexController::class, 'index'])->name('advisors');

    Route::resources([
        'catalogs' => \App\Http\Controllers\Admin\CatalogController::class,
        'courses' => \App\Http\Controllers\Admin\CourseController::class,
        'sections' => \App\Http\Controllers\Admin\SectionController::class,
    ]);
});

// messages
Route::group(['prefix' => '/messages', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/user-search', [\App\Http\Controllers\MessageController::class, 'userSearch'])
        ->name('messages.userSearch');
    Route::post('/create', [\App\Http\Controllers\MessageController::class, 'create'])
        ->name('messages.create');
    Route::post('/{conversation}/reply', [\App\Http\Controllers\MessageController::class, 'reply'])
        ->name('messages.reply');
    Route::get('/{conversation?}', [\App\Http\Controllers\MessageController::class, 'index'])
        ->name('messages.index');
});



Route::group(['prefix' => '/test'], function () {
    Route::get('/send-notification', function () {
        auth()->user()->notify(new \App\Notifications\TestNotification);
    });
});
