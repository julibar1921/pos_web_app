<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // All permissions used by controllers
        $permissions = [
            // Categories
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            // Products
            'view products',
            'create products',
            'edit products',
            'delete products',
            // Settings
            'view settings',
            'edit settings',
            // POS & Orders
            'access pos',
            'view orders',
            'delete orders',
            // Customers
            'view customers',
            'create customers',
            'edit customers',
            'delete customers',
            // Expenses
            'view expenses',
            'create expenses',
            'delete expenses',
            // Suppliers
            'view suppliers',
            'create suppliers',
            'edit suppliers',
            'delete suppliers',
            // Stock
            'view stock',
            'edit stock',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission],
                ['guard_name' => 'web']
            );
        }

        // Create admin role and give all permissions
        $adminRole = Role::updateOrCreate(['name' => 'admin'], ['guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        // Create vendeur role with limited permissions
        $vendeurRole = Role::updateOrCreate(['name' => 'vendeur'], ['guard_name' => 'web']);
        $vendeurRole->syncPermissions([
            'access pos',
            'view orders',
            'view products',
            'view customers',
            'create customers',
            'edit customers',
        ]);

        // Create admin user
        $user = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'     => 'Admin',
                'password' => bcrypt('12345678'),
            ]
        );

        $user->syncRoles(['admin']);

        // Seed settings
        $this->call(SettingSeeder::class);
    }
}
