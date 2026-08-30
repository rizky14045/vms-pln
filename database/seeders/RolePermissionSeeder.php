<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Permissions
        Permission::create(['name' => 'view dashboard']);
        Permission::create(['name' => 'approval employee']);
        Permission::create(['name' => 'approval visitor']);
        Permission::create(['name' => 'manage area']);
        Permission::create(['name' => 'manage device']);
        Permission::firstOrCreate(['name' => 'manage visitor card']);

        // Roles
        $admin = Role::create(['name' => 'super-admin']);
        $adminLobby  = Role::create(['name' => 'admin-lobby']);

        // Assign permission ke role
        $admin->givePermissionTo(['view dashboard', 'approval employee', 'approval visitor', 'manage area', 'manage device', 'manage visitor card']);
        $adminLobby->givePermissionTo(['view dashboard', 'approval visitor']);
    }
}
