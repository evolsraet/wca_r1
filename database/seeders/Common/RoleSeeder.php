<?php

namespace Database\Seeders\Common;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds for all environments.
     */
    public function run(): void
    {
        // Create roles and assign permissions
        $this->createUserRole();
        $this->createDealerRole();
        $this->createAdminRole();
    }

    /**
     * Create user role with permissions
     */
    protected function createUserRole(): void
    {
        $role = Role::firstOrCreate(['name' => 'user']);
        
        $permissions = ['act.user'];
        
        // Add additional permissions for dev/test environments
        if (in_array(config('app.env'), ['local', 'development', 'testing'])) {
            $permissions = array_merge($permissions, [
                'auction.create',
                'bid.create',
                'review.create',
            ]);
        }
        
        $role->syncPermissions($permissions);
    }

    /**
     * Create dealer role with permissions
     */
    protected function createDealerRole(): void
    {
        $role = Role::firstOrCreate(['name' => 'dealer']);
        
        $permissions = ['act.dealer'];
        
        // Add additional permissions for dev/test environments
        if (in_array(config('app.env'), ['local', 'development', 'testing'])) {
            $permissions = array_merge($permissions, [
                'auction.create',
                'auction.edit',
                'bid.create',
                'review.create',
                'review.edit',
            ]);
        }
        
        $role->syncPermissions($permissions);
    }

    /**
     * Create admin role with all permissions
     */
    protected function createAdminRole(): void
    {
        $role = Role::firstOrCreate(['name' => 'admin']);
        
        // Admin gets all permissions
        $role->syncPermissions(\Spatie\Permission\Models\Permission::all());
    }
}