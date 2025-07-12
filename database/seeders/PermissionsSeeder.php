<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view-users', 'create-user', 'edit-user', 'delete-user',
            'view-roles', 'create-role', 'edit-role', 'delete-role',
            'view-archives', 'create-archive', 'edit-archive', 'delete-archive', 'download-archive',
            'create-disposition', 'view-disposition',
            'view-opd-requests',      // <-- TAMBAHAN BARU
            'process-opd-requests',   // <-- TAMBAHAN BARU
            'view-opd-documents'      // <-- TAMBAHAN BARU
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}