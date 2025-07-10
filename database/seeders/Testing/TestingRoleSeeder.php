<?php

namespace Database\Seeders\Testing;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TestingRoleSeeder extends Seeder
{
    /**
     * Run the database seeds for testing environment.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'act.user',
            'act.dealer',
            'act.admin',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $roleUser = Role::firstOrCreate(['name' => 'user']);
        $roleUser->givePermissionTo('act.user');

        $roleDealer = Role::firstOrCreate(['name' => 'dealer']);
        $roleDealer->givePermissionTo('act.dealer');

        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->givePermissionTo(['act.user', 'act.dealer', 'act.admin']);
    }
}