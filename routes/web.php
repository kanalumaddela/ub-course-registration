<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

// new dashboard
Route::get('/new', [\App\Http\Controllers\IndexController::class, 'index'])->name('index');

// all courses
Route::get('/search', \App\Http\Controllers\SearchController::class)->name('search');

// register for course section
Route::post('/register-section/{courseSection}', [\App\Http\Controllers\RegisterController::class, 'register'])->name('register.section');


// catalog
Route::get('/catalogs', [\App\Http\Controllers\CatalogController::class, 'index'])->name('catalogs.index');
Route::get('/catalogs/{catalog}', [\App\Http\Controllers\CatalogController::class, 'view'])->name('catalogs.view');












Route::group(['prefix' => '/test'], function () {
    Route::get('/departments', function () {
        $departments = \App\Models\Department::withCount('courses')->orderBy('name', 'asc')->paginate(20);

//        dd($departments[0]['courses_count']);

        return Inertia::render('Test/Departments', [
            'departments' => $departments
        ]);
    })->name('test.departments');

    Route::get('/courses', function () {
        $courses = \App\Models\Course::orderBy('name', 'asc')->paginate(18);

//        dd($courses);

        return Inertia::render('Test/Courses', [
            'courses' => $courses
        ]);
    })->name('test.courses');

    Route::get('/department', function () {
        $department = \App\Models\Department::with('courses')->find(1);

        return response()->json($department, 200, [], JSON_PRETTY_PRINT);
    });
});
