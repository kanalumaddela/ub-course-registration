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
            [
                'user_id'           => 1,
                'course_section_id' => 1388,
                'status'            => 'planned',
            ],
            [
                'user_id'           => 1,
                'course_section_id' => 1704,
                'status'            => 'planned',
            ],
        ]);

        $users = DB::table('users')->select('id')
            ->join('model_has_roles', 'model_has_roles.model_id', 'users.id')
            ->where('model_has_roles.role_id', 3)
            ->where('model_has_roles.model_type', 'App\Models\User')
            ->where('users.id', '<>', 1)
            ->inRandomOrder()
            ->limit(75)
            ->get()
            ->pluck('id')
            ->toArray();

        $sections = DB::table('course_sections')
            ->select('course_sections.id')
            ->join('catalogs', 'catalogs.id', '=', 'course_sections.catalog_id')
            ->join('course_section_schedules', 'course_section_schedules.course_section_id', '=', 'course_sections.id')

            ->where('catalogs.is_active', 1)
            ->whereIn('catalogs.semester', ['spring', 'summer'])
            ->whereRaw('course_sections.end_date > CURDATE()')
            ->groupBy('course_sections.id')
            ->get()
            ->pluck('id')
            ->toArray();

        $inserts = [];
        $hashCheck  = [];

        $status = ['pending', 'approved', 'denied', 'planned', 'registered'];

        foreach ($users as $id) {
//            if ($id === 1) {
//                continue;
//            }

            foreach (array_fill(0, rand(2, 6), '') as $tmp) {

                $section_id = $sections[array_rand($sections)];
                $status_type = $status[array_rand($status)];

                $hash = md5($id.'-'.$section_id.'-'.$status_type);

                if (isset($hashCheck[$hash])) {
                    continue;
                } else {
                    $hashCheck[$hash] = '';
                }

                $inserts[] = [
                    'user_id'           => $id,
                    'course_section_id' => $section_id,
                    'status'            => $status_type,
                ];
            }
        }

        DB::table('student_registrations')->insert($inserts);
    }
}
