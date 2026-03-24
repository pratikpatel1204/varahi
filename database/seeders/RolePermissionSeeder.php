<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'manage users',
            'manage roles',
            'view dashboard',
            'manage office work',
            'manage sales',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles
        $superAdmin   = Role::firstOrCreate(['name' => 'super admin']);
        $admin        = Role::firstOrCreate(['name' => 'admin']);
        $officeEmployee = Role::firstOrCreate(['name' => 'office employee']);
        $salesEmployee  = Role::firstOrCreate(['name' => 'sales employee']);

        // Assign Permissions to roles
        $superAdmin->syncPermissions(Permission::all()); // Full access

        $admin->syncPermissions([
            'view dashboard',
            'manage users',
            'manage office work'
        ]);

        $officeEmployee->syncPermissions([
            'view dashboard',
            'manage office work'
        ]);

        $salesEmployee->syncPermissions([
            'view dashboard',
            'manage sales'
        ]);

        // Assign super admin role to first user (optional)
        $user = User::find(1);
        if ($user) {
            $user->assignRole('super admin');
        }
    }
}
