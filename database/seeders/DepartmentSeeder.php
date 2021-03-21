<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = json_decode(file_get_contents(__DIR__.'/_json/departments.json'));

        try {
            DB::beginTransaction();

            foreach ($departments as $insert) {
                DB::statement($insert);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
