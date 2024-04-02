<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateTestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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

        // 카테고리
        Category::create(['name' => 'Vue.js']);
        Category::create(['name' => 'Cat 2']);
    }
}
