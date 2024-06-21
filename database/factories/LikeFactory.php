<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Auction;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Like; // Ensure your model namespace is correct, considering the renaming

class LikeFactory extends Factory
{
    protected $model = Like::class;

    public function definition(): array
    {
        do {
            $user = User::role('dealer')->inRandomOrder()->first();
            $auction = Auction::inRandomOrder()->first();

            if (!$user || !$auction) {
                return []; // 유저 또는 경매가 없으면 빈 배열 반환
            }

            // 이미 존재하는 조합인지 확인
            $exists = Like::where('user_id', $user->id)
                ->where('likeable_id', $auction->id)
                ->where('likeable_type', Auction::class)
                ->exists();

            // 로그 파일에 진행 상황 기록
            Log::info("LikeFactory: {$auction->id}-{$user->id}, Exists: {$exists}");
        } while ($exists); // 이미 조합이 존재하면 반복
        return [
            'likeable_type' => Auction::class,
            'likeable_id' => $auction->id,
            'user_id' => $user->id,
        ];
    }
}
