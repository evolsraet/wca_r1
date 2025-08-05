<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Common\CarDatabaseSeeder;

/**
 * 차량 DB만 단독으로 시드하는 전용 시더
 * 
 * 사용법:
 * php artisan db:seed --class=CarOnlySeeder
 */
class CarOnlySeeder extends Seeder
{
    /**
     * 차량 DB만 시드 실행
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('🚗 차량 DB 전용 시드 실행');
        $this->command->info('========================================');
        
        $this->call([
            CarDatabaseSeeder::class
        ]);
        
        $this->command->info('========================================');
        $this->command->info('✅ 차량 DB 시드 완료!');
    }
}