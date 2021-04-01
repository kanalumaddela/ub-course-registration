<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $terms = json_decode(file_get_contents(__DIR__.'/_json/catalogs.json'));

        try {
            DB::beginTransaction();

            foreach ($terms as $i => $insert) {
                DB::statement($insert);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
