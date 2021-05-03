<?php

namespace Database\Seeders;

use App\Models\StudentRegistration;
use App\Models\User;
use App\Notifications\StudentRegistered;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $student = User::create([
            'name'     => 'Student',
            'email'    => 'student@bridgeport.edu',
            'password' => Hash::make('student123'),
        ]);
        $student->assignRole('student');

        $advisor = User::create([
            'name'     => 'Advisor',
            'email'    => 'advisor@bridgeport.edu',
            'password' => Hash::make('advisor123'),
        ]);
        $advisor->assignRole('advisor');
        DB::table('department_advisors')->insert([
            'department_id' => 45,
            'user_id'       => $advisor->id,
        ]);

        $registration = StudentRegistration::create([
            'user_id'           => $student->id,
            'course_section_id' => 1388,
            'status'            => 'pending',
        ]);

        $advisor->notify(new StudentRegistered($registration));

        $admin = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@bridgeport.edu',
            'password' => Hash::make('admin123'),
        ]);
        $admin->assignRole('admin');
    }
}
