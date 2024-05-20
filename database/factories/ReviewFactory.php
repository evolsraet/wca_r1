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
        static $reviewedAuctions = [];

        $auction = \App\Models\Auction::where('status', 'done')
            ->doesntHave('reviews')
            ->whereNotIn('id', $reviewedAuctions)
            ->inRandomOrder()
            ->first();

        if (!$auction) {
            return []; // Return an empty array if there are no available auctions
        }

        // Add the auction id to the reviewedAuctions array
        $reviewedAuctions[] = $auction->id;

        $dealerId = \App\Models\User::role('dealer')->inRandomOrder()->first()->id;

        if (!$dealerId) {
            return []; // Return an empty array if there are no available dealers
        }

        $result = [
            'auction_id' => $auction->id,
            'user_id' => $auction->user_id,
            'dealer_id' => $dealerId,
            'star' => $this->faker->randomFloat(1, 0, 5), // 0.0~5.0 사이에서 0.5 단위로 랜덤 값을 생성합니다.
            'content' => $this->faker->sentence, // 랜덤 문장 생성
        ];

        // dump($result);

        return $result;
    }
}