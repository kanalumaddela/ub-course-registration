<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sections = json_decode(file_get_contents(__DIR__.'/_json/course_sections.json'));
        $schedules = json_decode(file_get_contents(__DIR__.'/_json/course_section_schedules.json'));

        try {
            DB::beginTransaction();

            foreach ($sections as $i => $insert) {
                DB::statement($insert);
            }

            DB::commit();

        } catch (\Exception $e) {

            var_dump($e->getMessage());

            DB::rollBack();
        }

        try {
            DB::beginTransaction();

            foreach ($schedules as $i => $insert) {
                DB::statement($insert);
            }

            DB::commit();

        } catch (\Exception $e) {

            var_dump($e->getMessage());

            DB::rollBack();
        }
    }
}
