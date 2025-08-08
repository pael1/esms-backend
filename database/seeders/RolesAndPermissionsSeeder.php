<?php

namespace Database\Seeders;

use App\Models\UserAccount;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = 'api'; // Use 'api' guard for Nuxt 3 frontend

        // Define permissions
        $permissions = [
            'view dashboard',
            'manage users',
            'edit articles',
            'delete articles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $guard,
            ]);
        }

        // Define roles and assign permissions
        $roles = [
            'admin' => $permissions,
            'editor' => ['edit articles', 'view dashboard'],
            'viewer' => ['view dashboard'],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => $guard,
            ]);

            $role->syncPermissions($rolePermissions);
        }

        // Assign role to user by EmpId
        $user = UserAccount::where('SystemUser_EmpId', 12345)->first();

        if ($user) {
            // $user->assignRole('admin'); // Will work since guard matches
            $user->assignRole(Role::where('name', 'admin')->where('guard_name', 'api')->first());
        }
    }
}
