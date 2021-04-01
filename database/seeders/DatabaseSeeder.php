<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            DepartmentSeeder::class,
            CourseSeeder::class,
            CourseSectionSeeder::class,
            CatalogSeeder::class,
            RolesPermissionsSeeder::class,
            StudentSeeder::class
        ]);

        User::create([
            'name' => 'kanalumaddela',
            'email' => 'spam@maddela.org',
            'password' => Hash::make('password'),
        ]);
    }
}
