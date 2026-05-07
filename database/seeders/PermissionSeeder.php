<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view products',
            'create products',
            'edit products',
            'delete products',
            'view settings',
            'edit settings',
            'access pos',
            'view orders',
            'delete orders',
            'view customers',
            'create customers',
            'edit customers',
            'delete customers',
            'view expenses',
            'create expenses',
            'delete expenses',
            'view suppliers',
            'create suppliers',
            'edit suppliers',
            'delete suppliers',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission], ['guard_name' => 'web']);
        }

        // Create Roles
        $adminRole = Role::updateOrCreate(['name' => 'admin']);
        $vendeurRole = Role::updateOrCreate(['name' => 'vendeur']);

        // Assign all permissions to Admin
        $adminRole->syncPermissions(Permission::all());

        // Assign limited permissions to Vendeur
        $vendeurRole->syncPermissions([
            'access pos',
            'view orders',
            'view products',
            'view customers',
            'create customers',
            'edit customers',
        ]);

        // Assign admin role to the first user if exists
        $user = \App\Models\User::first();
        if ($user && !$user->hasRole('admin')) {
            $user->assignRole('admin');
        }
    }
}
