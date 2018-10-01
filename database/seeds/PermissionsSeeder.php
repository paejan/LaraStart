<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Seed the Permissions and Roles needed in the application.
     *
     * @return void
     */
    public function run()
    {
        // Roles
        $admin = Role::create(['name' => 'Administrator']);

        // Permissions
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);

        // Give Administrator all permissions.
        $admin->givePermissionTo(Permission::all());
    }
}
