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
            'users.edit',
            'users.deactivate',
            'users.delete',
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.deactivate',
            'categories.delete',
            'raw-materials.view',
            'raw-materials.create',
            'raw-materials.edit',
            'raw-materials.deactivate',
            'raw-materials.delete',
            'units.view',
            'units.create',
            'units.edit',
            'units.deactivate',
            'units.delete',
            'warehouses.view',
            'warehouses.create',
            'warehouses.edit',
            'warehouses.deactivate',
            'warehouses.delete',
            'suppliers.view',
            'suppliers.create',
            'suppliers.edit',
            'suppliers.deactivate',
            'suppliers.delete',
            'responsibles.view',
            'responsibles.create',
            'responsibles.edit',
            'responsibles.deactivate',
            'responsibles.delete',
            'raw-material-documents.view',
            'raw-material-documents.create',
            'raw-material-documents.edit',
            'raw-material-documents.delete',
            'raw-material-documents.accept',
            'raw-material-documents.reject',
            'raw-material-documents.cancel',
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
