<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = json_decode(file_get_contents(__DIR__.'/_json/courses.json'));

        try {
            DB::beginTransaction();

            foreach ($courses as $i => $insert) {
                DB::statement($insert);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
