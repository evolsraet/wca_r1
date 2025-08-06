<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 기본권한들 생성
        $permissions = [
            'role.super',
            'role.admin',
            'role.dealer',
            'role.user',
            'act.super',
            'act.admin',
            'act.dealer',
            'act.user',
            'act.login',
        ];

        $roles = [
            'super' => [
                'role.super',
                'act.super',
                'act.admin',
                'act.dealer',
                'act.user',
                'act.login',
            ],
            'admin' => [
                'role.admin',
                'act.admin',
                'act.dealer',
                'act.user',
                'act.login',
            ],
            'dealer' => [
                'role.dealer',
                'act.dealer',
                'act.login',
            ],
            'user' => [
                'role.user',
                'act.user',
                'act.login',
            ],
        ];

        // 퍼미션 등록 (중복 방지)
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 롤 등록 및 싱크 (중복 방지)
        foreach ($roles as $role_name => $perms) {
            $role = Role::firstOrCreate(['name' => $role_name]);
            $role->syncPermissions($perms);
        }
    }
}
