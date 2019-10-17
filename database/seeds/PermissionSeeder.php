<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class RolesAndPermissionsSeeder extends Seeder {

    public function run() {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
    }

}

class PermissionSeeder extends Seeder {

    public function run() {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'admin',

            'users block',
            'show users',
            'add users',
            'edit users',
            'delete users',
            'show roles',
            'add roles',
            'edit roles',
            'delete roles',
            'show permission',
            'add permission',
            'edit permission',
            'delete permission',            
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

}

class RoleSeeder extends Seeder {

    public function run() {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            'SuperAdmin',
            'Administrator',
            'Manager',
        ];

        foreach ($roles as $role) {
            $addedRole = Role::create(['name' => $role]);

            switch ($role) {
                case 'SuperAdmin':
                    $addedRole->givePermissionTo(Permission::all());

                    $user = User::where('name', 'superadmin')->first();
                    if ($user)
                        $user->assignRole(['SuperAdmin']);
                    break;
                default :
                    $addedRole->givePermissionTo(['admin']);
                    break;
            }
        }
    }

}
