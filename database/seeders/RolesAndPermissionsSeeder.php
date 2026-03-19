<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Spatie\Permission\PermissionRegistrar;

final class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'users.toggle',

            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',

            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',
            'categories.toggle',

            'raw-materials.view',
            'raw-materials.create',
            'raw-materials.update',
            'raw-materials.delete',
            'raw-materials.toggle',

            'units.view',
            'units.create',
            'units.update',
            'units.delete',
            'units.toggle',

            'warehouses.view',
            'warehouses.create',
            'warehouses.update',
            'warehouses.delete',
            'warehouses.toggle',

            'suppliers.view',
            'suppliers.create',
            'suppliers.update',
            'suppliers.delete',
            'suppliers.toggle',

            'responsibles.view',
            'responsibles.create',
            'responsibles.update',
            'responsibles.delete',
            'responsibles.toggle',

            'raw-material-documents.view',
            'raw-material-documents.create',
            'raw-material-documents.update',
            'raw-material-documents.delete',
            'raw-material-documents.accept',
            'raw-material-documents.reject',
            'raw-material-documents.cancel',

            'raw-material-batches.view',
            'raw-material-movements.view',
            'raw-material-stocks.view',

            'export'
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $role->syncPermissions($permissions);

        $user = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin', 'password' => bcrypt('123456788')]
        );

        $user->assignRole($role);
    }
}
