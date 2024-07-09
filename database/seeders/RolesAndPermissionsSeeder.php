<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate the roles table
        // DB::table('roles')->truncate();

         // Define Roles
         $roles = [
            'admin',
            'user'
        ];

        // Define Permissions
        $permissions = [
            'view followups',
            'create comment followup',
            'create solution followup',
            'update followup',
            'delete followup'
        ];

        // Create Roles if they don't exist
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Create Permissions if they don't exist
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Assign Permissions to Roles
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        $adminRole->syncPermissions($permissions);
        $userRole->syncPermissions([
            'view followups',
            'create comment followup'
        ]);
    }
}
