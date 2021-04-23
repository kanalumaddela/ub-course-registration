<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = [
            'admin'   => [
                'count' => null,
                'perms' => [],
            ],
            'advisor' => [
                'count' => Department::count(),
                'perms' => ['manage-registrations'],
            ],
            'student' => [
                'count' => 500,
                'perms' => ['register-for-classes'],
            ],
        ];

//        print_r($rolesWithPermissions);

        foreach ($rolesWithPermissions as $roleName => $data) {
            /**
             * @var $role Role
             */
            $role = Role::create(['name' => $roleName]);

            $permissions = $data['perms'];

            foreach ($permissions as $perm) {
                /**
                 * @var $permission Permission
                 */
                $permission = Permission::create(['name' => $perm]);
                $role->givePermissionTo($permission);
            }

            if (env('APP_SEED') && $data['count']) {
                $users = User::factory()
                    ->count($data['count'])
//                    ->has(
//                        Conversation::factory()
//                            ->for()
//                            ->count(2)
//                            ->has(
//                                Message::factory()->count(7)
//                            )
//                    )
                    ->create();

                if ($roleName === 'advisor') {
                    $departmentCount = 1;
                }

                foreach ($users as $user) {
                    $user->assignRole($role);

                    if ($roleName === 'advisor') {
                        if ($departmentCount > $data['count']) {
                            $departmentCount = rand(1, $data['count']);
                        }

                        DB::table('department_advisors')->insert([
                            'department_id' => $departmentCount,
                            'user_id'       => $user->id,
                        ]);

                        $departmentCount++;
                    }
                }
            }
        }
    }
}
