<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Designation permissions
            'view designations',
            'create designations',
            'edit designations',
            'delete designations',

            // Leave permissions
            'view leaves',
            'create leaves',
            'edit leaves',
            'delete leaves',
            'approve leaves',
            'reject leaves',

            // Leave type permissions
            'view leave types',
            'create leave types',
            'edit leave types',
            'delete leave types',

            // Holiday permissions
            'view holidays',
            'create holidays',
            'edit holidays',
            'delete holidays',

            // Project permissions
            'view projects',
            'create projects',
            'edit projects',
            'delete projects',

            // Task time entry permissions
            'view task time entries',
            'create task time entries',
            'edit task time entries',
            'delete task time entries',

            // Attendance permissions
            'view attendances',
            'create attendances',
            'edit attendances',
            'delete attendances',

            // Report permissions
            'view reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $hr = Role::create(['name' => 'hr']);
        $hr->givePermissionTo([
            'view users',
            'create users',
            'edit users',
            'view designations',
            'create designations',
            'edit designations',
            'view leaves',
            'approve leaves',
            'reject leaves',
            'view leave types',
            'create leave types',
            'edit leave types',
            'view holidays',
            'create holidays',
            'edit holidays',
            'delete holidays',
            'view projects',
            'view task time entries',
            'view attendances',
            'create attendances',
            'edit attendances',
            'view reports',
        ]);

        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo([
            'view users',
            'view designations',
            'view leaves',
            'approve leaves',
            'reject leaves',
            'view leave types',
            'view holidays',
            'view projects',
            'create projects',
            'edit projects',
            'view task time entries',
            'create task time entries',
            'edit task time entries',
            'view attendances',
            'view reports',
        ]);

        $employee = Role::create(['name' => 'employee']);
        $employee->givePermissionTo([
            'view leaves',
            'create leaves',
            'edit leaves',
            'view leave types',
            'view holidays',
            'view projects',
            'view task time entries',
            'create task time entries',
            'edit task time entries',
            'view attendances',
        ]);
    }
}
