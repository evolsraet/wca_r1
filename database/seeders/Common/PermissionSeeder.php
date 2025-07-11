<?php

namespace Database\Seeders\Common;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds for all environments.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions based on environment
        $permissions = $this->getPermissions();

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }

    /**
     * Get permissions based on environment
     */
    protected function getPermissions(): array
    {
        $basePermissions = [
            'act.user',    // 일반 사용자 권한
            'act.dealer',  // 딜러 권한
            'act.admin',   // 관리자 권한
        ];

        // Development/Testing environments get additional permissions
        if (in_array(config('app.env'), ['local', 'development', 'testing'])) {
            return array_merge($basePermissions, [
                'auction.create',
                'auction.edit',
                'auction.delete',
                'bid.create',
                'review.create',
                'review.edit',
                'review.delete',
            ]);
        }

        return $basePermissions;
    }
}