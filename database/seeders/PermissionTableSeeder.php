<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'role.super',
            'role.admin',
            'role.dealer',
            'role.user',
            'act.super',
            'act.admin',
            'act.dealer',
            'act.user',
        ];

        $roles = [
            'super' => [
                'role.super',
                'act.super',
                'act.admin',
                'act.dealer',
                'act.user',
            ],
            'admin' => [
                'role.admin',
                'act.admin',
                'act.dealer',
                'act.user',
            ],
            'dealer' => [
                'role.dealer',
                'act.dealer',
            ],
            'user' => [
                'role.user',
                'act.user',
            ],
        ];

        // 퍼미션 등록
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 롤 등록 및 싱크
        foreach ($roles as $role_name => $perms) {
            $role = Role::create(['name' => $role_name]);
            $role->syncPermissions($perms);
        }
    }
}
