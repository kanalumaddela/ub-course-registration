<?php

namespace Database\Seeders;

use App\Models\User;
use App\Notifications\TestNotification;
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
        $user = User::create([
            'name' => 'Samuel Maddela',
            'email' => 'spam@maddela.org',
            'password' => Hash::make('password'),
        ]);

        $user->notify(new TestNotification);

        $this->call([
            UBSampleDataSeeder::class,
            RolesPermissionsSeeder::class,
        ]);

        if (env('APP_SEED')) {
            $this->call([
                StudentSeeder::class,
                ConversationMessageSeeder::class
            ]);
        }

        if (env('DEMO_MODE', false)) {
            $this->call(DemoSeeder::class);
        }
    }
}
