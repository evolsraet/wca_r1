<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
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

        // 퍼미션 등록
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 롤 등록 및 싱크
        foreach ($roles as $role_name => $perms) {
            $role = Role::create(['name' => $role_name]);
            $role->syncPermissions($perms);
        }

        // 테스트용 유저 생성
        $users = [];

        // 슈퍼 (시스템)
        $users['super'] = [
            'name' => '시스템',
            'email' => 'super@super.com',
            'password' => '123123123',
            'status' => 'ok',
            'phone' => '010-3425-8175',
        ];

        // 관리자
        $users['admin'] = [
            'name' => '데모관리자',
            'email' => 'admin@demo.com',
            'password' => '123123123',
            'status' => 'ok',
            'phone' => '010-3425-8175',
        ];

        // 사용자
        $users['user'] = [
            'name' => '데모유저',
            'email' => 'user@demo.com',
            'password' => '123123123',
            'status' => 'ok',
            'phone' => '010-3425-8175',
        ];

        // 딜러
        $users['dealer'] = [
            'name' => '데모딜러',
            'email' => 'dealer@demo.com',
            'password' => '123123123',
            'status' => 'ok',
            'phone' => '010-3425-8175',
        ];

        foreach ($users as $role => $user) {
            $created_user = User::create($user);
            $created_user->assignRole($role);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 롤과 퍼미션 삭제
        $roles = ['super', 'admin', 'dealer', 'user'];
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

        foreach ($roles as $role) {
            Role::findByName($role)->delete();
        }

        foreach ($permissions as $permission) {
            Permission::findByName($permission)->delete();
        }

        // 유저 삭제
        User::whereIn('email', [
            'super@super.com',
            'admin@demo.com',
            'user@demo.com',
            'dealer@demo.com'
        ])->delete();
    }
};
