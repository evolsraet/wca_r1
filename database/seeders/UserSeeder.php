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
    }
}
