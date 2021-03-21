<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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

        $roles = [
            'admin',
            'advisor',
            'student',
        ];

        $permissions = [
            'manage-registration',
            'register-for-classes',
        ];

        $created = [
            'roles' => [],
//            'permissions' => [],
        ];

        $initial_perms = [
            'advisor' => ['manage-registration'],
            'student' => ['register-for-classes'],
        ];

        foreach ($roles as $role) {
            $created['roles'][$role] = Role::create(['name' => $role]);
        }

        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        foreach ($initial_perms as $role => $perms) {
            $role = $created['roles'][$role];
            $role->givePermissionTo($perms);
        }
    }
}
