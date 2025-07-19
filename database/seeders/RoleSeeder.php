<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $admin = Role::create(['name' => 'Admin']);
        $customer = Role::create(['name' => 'Customer']);
        $reseller = Role::create(['name' => 'Reseller']);

        // Create permissions
        $permissions = [
            // Admin permissions
            'manage-users',
            'manage-roles',
            'manage-servers',
            'manage-billing',
            'view-admin-panel',
            
            // Reseller permissions
            'manage-customers',
            'create-servers',
            'view-reseller-panel',
            
            // Customer permissions
            'manage-own-servers',
            'view-billing',
            'make-payments',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $admin->givePermissionTo(Permission::all());
        
        $reseller->givePermissionTo([
            'manage-customers',
            'create-servers',
            'view-reseller-panel',
            'manage-own-servers',
            'view-billing',
            'make-payments',
        ]);
        
        $customer->givePermissionTo([
            'manage-own-servers',
            'view-billing',
            'make-payments',
        ]);
    }
}
