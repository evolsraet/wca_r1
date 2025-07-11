<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // APP_ENV 환경변수 기반 시더 실행
        switch (config('app.env')) {
            case 'testing':
                $this->call([
                    Common\PermissionSeeder::class,
                    Common\RoleSeeder::class,
                    // 테스트에 필요한 최소 데이터만
                ]);
                break;

            case 'local':
            case 'development':
                $this->call([
                    // 공통 시더
                    Common\PermissionSeeder::class,
                    Common\RoleSeeder::class,
                    // 개발 환경용 시더
                    Development\CreateTestUserSeeder::class,
                    Development\UserSeeder::class,
                    Development\DealerSeeder::class,
                    Development\AuctionSeeder::class,
                    Development\BidSeeder::class,
                    Development\ReviewSeeder::class,
                    Development\LikeSeeder::class,
                    Development\ArticleSeeder::class,
                    Development\CommentSeeder::class,
                    Development\AddressbookSeeder::class,
                ]);
                break;

            case 'production':
            case 'staging':
                // Production 환경용 최소 시더
                $this->call([
                    Common\PermissionSeeder::class,
                    Common\RoleSeeder::class,
                    Production\AdminSeeder::class,
                ]);
                break;

            default:
                throw new \Exception("환경 설정 안됨: " . config('app.env'));
        }
    }
}
