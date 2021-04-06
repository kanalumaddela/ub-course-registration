<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UBSampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dir = base_path().'/database/seeders/_json/_queries/';

        $tables = [
            // no relations
            'catalogs',
            'departments',
            'locations',
            'buildings',

            // has relations
            'courses',
            'course_sections',
            'course_section_schedules',
        ];

        foreach ($tables as $table) {
            if (!file_exists($dir.$table.'.json')) {
                continue;
            }

            $data = json_decode(file_get_contents($dir.$table.'.json'), true);

            if (count($data) > 0) {
                try {
                    DB::beginTransaction();

                    foreach ($data as $insert) {
                        DB::statement($insert);
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    print "\n".$e->getMessage()."\n";
                    DB::rollBack();
                }
            }

        };
    }
}
