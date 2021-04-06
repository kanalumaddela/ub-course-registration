<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('student_registrations')->insert([
//            [
//                'user_id' => 1,
//                'course_section_id' => 1212,
//                'status' => 'approved',
//            ],
//            [
//                'user_id' => 1,
//                'course_section_id' => 1499,
//                'status' => 'approved',
//            ],
//            [
//                'user_id' => 1,
//                'course_section_id' => 3021,
//                'status' => 'pending',
//            ],
        ]);
    }
}
