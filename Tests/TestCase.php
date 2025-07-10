<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\Testing\TestingRoleSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // 테스트 환경에서 필수 시드 실행
        $this->seed(TestingRoleSeeder::class);
    }

    /**
     * 테스트용 일반 사용자 생성
     */
    protected function createTestUser(array $attributes = []): User
    {
        $user = User::factory()->create(array_merge([
            'status' => 'ok',
        ], $attributes));

        $user->assignRole('user');

        return $user;
    }

    /**
     * 테스트용 딜러 생성
     */
    protected function createTestDealer(array $attributes = []): User
    {
        $user = User::factory()->create(array_merge([
            'status' => 'ok',
            'company_name' => '테스트 자동차',
            'business_number' => '123-45-67890',
        ], $attributes));

        $user->assignRole('dealer');

        return $user;
    }

    /**
     * 테스트용 관리자 생성
     */
    protected function createTestAdmin(array $attributes = []): User
    {
        $user = User::factory()->create(array_merge([
            'status' => 'ok',
        ], $attributes));

        $user->assignRole('admin');

        return $user;
    }
}