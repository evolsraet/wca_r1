<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 'user'와 'dealer' 역할이 데이터베이스에 존재하는지 확인하고, 없으면 생성
        $roles = ['user', 'dealer'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // User 팩토리를 사용하여 10개의 사용자를 생성하고 각각에게 랜덤 역할을 할당
        User::factory(30)->create()->each(function ($user) {
            // 'user' 또는 'dealer' 역할 중 하나를 랜덤으로 선택하여 할당
            $randomRole = collect(['user', 'dealer'])->random();
            $user->assignRole($randomRole);
        });

        // 추가 유저 생성
        $users = [
            [
                'name' => '데모유저1',
                'email' => 'user1@demo.com',
                'password' => '123123123',
                'status' => 'ok',
                'phone' => '010-4444-4441',
                'role' => 'user',
            ],
            [
                'name' => '데모유저2',
                'email' => 'user2@demo.com',
                'password' => '123123123',
                'status' => 'ok',
                'phone' => '010-4444-4442',
                'role' => 'user',
            ],
            [
                'name' => '데모유저3',
                'email' => 'user3@demo.com',
                'password' => '123123123',
                'status' => 'ok',
                'phone' => '010-4444-4443',
                'role' => 'user',
            ],
            [
                'name' => '데모유저4',
                'email' => 'user4@demo.com',
                'password' => '123123123',
                'status' => 'ok',
                'phone' => '010-4444-4444',
                'role' => 'user',
            ],
            [
                'name' => '부산유저1',
                'email' => 'busanuser@busancar.org',
                'password' => '123123123',
                'status' => 'ok',
                'phone' => '010-1515-5554',
                'role' => 'user',
            ],
            [
                'name' => '데모딜러1',
                'email' => 'dealer1@demo.com',
                'password' => '123123123',
                'status' => 'ok',
                'phone' => '010-5555-5551',
                'role' => 'dealer',
            ],
            [
                'name' => '데모딜러2',
                'email' => 'dealer2@demo.com',
                'password' => '123123123',
                'status' => 'ok',
                'phone' => '010-5555-5552',
                'role' => 'dealer',
            ],
            [
                'name' => '데모딜러3',
                'email' => 'dealer3@demo.com',
                'password' => '123123123',
                'status' => 'ok',
                'phone' => '010-5555-5553',
                'role' => 'dealer',
            ],
            [
                'name' => '데모딜러4',
                'email' => 'dealer4@demo.com',
                'password' => '123123123',
                'status' => 'ok',
                'phone' => '010-5555-5554',
                'role' => 'dealer',
            ],
            [
                'name' => '부산딜러1',
                'email' => 'busandealer@busancar.org',
                'password' => '123123123',
                'status' => 'ok',
                'phone' => '010-1515-5555',
                'role' => 'dealer',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
                'status' => $userData['status'],
                'phone' => $userData['phone'],
            ]);

            $user->assignRole($userData['role']);
        }
    }
}
