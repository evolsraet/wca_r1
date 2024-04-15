<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $auction = \App\Models\Auction::where('status', 'done')->doesntHave('reviews')->inRandomOrder()->first();
        $dealerId = \App\Models\User::role('dealer')->inRandomOrder()->first()->id;
        return [
            'auction_id' => $auction->id,
            'user_id' => $auction->user_id,
            'dealer_id' => $dealerId,
            'star' => $this->faker->randomFloat(1, 0, 5), // 0.0~5.0 사이에서 0.5 단위로 랜덤 값을 생성합니다.
            'content' => $this->faker->sentence, // 랜덤 문장 생성
        ];
    }
}
