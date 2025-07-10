<?php

namespace Database\Seeders\Testing;

use Illuminate\Database\Seeder;

class TestingDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database for testing.
     */
    public function run(): void
    {
        // 테스트에 필요한 최소한의 시드만 실행
        $this->call([
            TestingRoleSeeder::class,
        ]);
    }
}