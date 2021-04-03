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


// catalog
Route::get('/catalogs', [\App\Http\Controllers\CatalogController::class, 'index'])->name('catalogs.index');
Route::get('/catalogs/{catalog}', [\App\Http\Controllers\CatalogController::class, 'view'])->name('catalogs.view');












Route::group(['prefix' => '/test'], function () {
    Route::get('/credits', function () {
        $creditsDesc = [
            'min' => 1,
            'max' => 6,
        ];
        $creditsSect = [
            'min' => 2,
            'max' => 4,
        ];

        $tmpMapping = [
            'desc' => 'creditsDesc',
            'sect' => 'creditsSect',
        ];

//        $tmpKeys = array_keys($tmpMapping);

        $toUse = 'desc';

        $var = $tmpMapping[$toUse];

        dump($var);

        dd($$var);
    });

    Route::get('/timezone', function () {
        dump(date_default_timezone_get());

        \Illuminate\Support\Facades\DB::table('course_section_schedules')->insert([
            'course_section_id' => 57,
            'days' => json_encode(['M', 'T', 'F']),
            'start_time' => DateTime::createFromFormat('G:i A', '9:30 AM')->format('H:i:s'),
            'end_time' => DateTime::createFromFormat('G:i A', '12:00 PM')->format('H:i:s'),
        ]);

        date_default_timezone_set('America/New_York');
        dump(date_default_timezone_get());

        \Illuminate\Support\Facades\DB::table('course_section_schedules')->insert([
            'course_section_id' => 57,
            'days' => json_encode(['M', 'T', 'F']),
            'start_time' => DateTime::createFromFormat('G:i A', '9:30 AM')->format('H:i:s'),
            'end_time' => DateTime::createFromFormat('G:i A', '12:00 PM')->format('H:i:s'),
        ]);

        dd(\Illuminate\Support\Facades\DB::table('course_section_schedules')->orderBy('id', 'desc')->limit(2)->get());
    });

    Route::get('/schedule', function () {
        $meeting_times = 'SU 10:00 AM - 2:00 PM; SU  10:00 AM - 2:00 PM; SU  10:00 AM - 2:00 PM; SU  10:00 AM - 2:00 PM; SU  10:00 AM - 2:00 PM; SU  10:00 AM - 2:00 PM';
        $days = 'SU, SU, SU, SU, SU, SU';
        $buildings = 'Mandeville Hall, Mandeville Hall, Mandeville Hall, Mandeville Hall, Mandeville Hall, Mandeville Hall';
        $rooms = '208, 210, 216, 218, 104, 107';
        $types = 'Lecture, Lecture, Lecture, Lecture, Lecture, Lecture';

        $meeting_times = !empty($meeting_times) ? \App\Console\Commands\GenerateSeedData::explodeTrim($meeting_times, ';') : [];
        $days = !empty($days) ? \App\Console\Commands\GenerateSeedData::explodeTrim($days) : [];
        $buildings = !empty($buildings) ? \App\Console\Commands\GenerateSeedData::explodeTrim($buildings) : [];
        $rooms = !empty($rooms) ? \App\Console\Commands\GenerateSeedData::explodeTrim($rooms) : [];
        $types = !empty($types) ? \App\Console\Commands\GenerateSeedData::explodeTrim($types) : [];

        dd(get_defined_vars());
    });

    Route::get('/regex', function () {

        dd(strtotime('9:30 AM'));

        $meeting = 'M W 9:30 AM - 12:00 PM';
        $replace = ['M', 'W'];

        $regex = '/\bM|\bT|\bW|\bTH|\bF|\bSU|\bS/';
        preg_match_all($regex, $meeting, $matches);
        $meeting = trim(preg_replace($regex, '', $meeting));

        dump($matches);
        dd($meeting);

        foreach ($replace as $toReplace) {
            $regex = '/\b%s/';
            $meeting = preg_replace(sprintf($regex, $toReplace), '', $meeting);
        }

        dd($meeting);

        $desc = 'n individual course of instruction. 1-6 semester hours';
        $desc = 'An introduction to the basic principles of Accounting, and how to account for business transactions. Emphasis on the understanding of how financial statements are prepared, and how they are used as a basis for decision making by business owners, investors, creditors, government and others interested in the financial condition of an economic entity and the result of its operations. Topics include Analyzing Transactions; the Matching Concept and the Adjusting Process; Completing the Accounting Cycle; Accounting for Merchandising Business; Accounting Systems, Internal Controls, and Cash; and Receivables. 3 semester hours ';
        $desc = '0 semester hour';

        $regex = '/(\d+) semester hour/';
        $regex = '/(\d+)?-?(\d+) semester hour/';

        preg_match($regex, $desc, $credit_matches);

        dd($credit_matches);
    });

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
