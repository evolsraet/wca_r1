<?php

namespace Database\Seeders\Development;

use App\Models\User;
use App\Models\Dealer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DealerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'dealer' 역할을 가진 모든 사용자들을 조회합니다.
        $dealerUsers = User::role('dealer')->get();

        // 각 'dealer' 사용자에 대해 Dealer 인스턴스를 생성합니다.
        foreach ($dealerUsers as $user) {
            Dealer::factory()->create([
                'user_id' => $user->id,
                // 필요하다면 나머지 필드에 대한 기본값을 여기에 추가합니다.
            ]);
        }
    }
}
