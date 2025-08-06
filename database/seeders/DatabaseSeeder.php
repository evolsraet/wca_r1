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
        // 공용 시더 최우선 실행
        $this->call([
            // 권한/역할/기본유저 최우선 실행
            Common\PermissionRoleSeeder::class, // 최우선 실행
            // 차량 DB (기본 데이터)
            Common\CarDatabaseSeeder::class,
            // 기본 시스템 관리자
            Common\UserAdminSeeder::class,
        ]);

        // APP_ENV 환경변수 기반 시더 실행
        switch (config('app.env')) {
            case 'testing':
                // $this->call([
                // ]);
                break;

            case 'local':
            case 'development':
                $this->call([
                    // 개발 환경용 시더
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
                // $this->call([
                // ]);
                break;

            default:
                throw new \Exception("환경 설정 안됨: " . config('app.env'));
        }
    }
}
