<?php

namespace Database\Seeders\Production;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds for production environment.
     * Creates only the initial admin account.
     */
    public function run(): void
    {
        // 초기 관리자 계정 생성
        // 주의: 운영 환경에서는 즉시 비밀번호를 변경해야 함
        $admin = User::firstOrCreate(
            ['email' => 'it@wecar-m.co.kr'],
            [
                'name' => '시스템 관리자',
                'phone' => '01034258175',
                'password' => '123123123',
                'status' => 'ok',
                'email_verified_at' => now(),
            ]
        );

        // 관리자 역할 부여
        $admin->assignRole('admin');
    }
}