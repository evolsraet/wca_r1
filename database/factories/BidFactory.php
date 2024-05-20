<?php

namespace Database\Factories;

use App\Models\Bid;
use Illuminate\Database\Eloquent\Factories\Factory;

class BidFactory extends Factory
{
    protected $model = Bid::class;

    public function definition(): array
    {
        return [
            'auction_id' => \App\Models\Auction::where(function ($query) {
                $query->where('status', 'ing')
                    ->orWhere('status', 'done');
            })->inRandomOrder()->first()->id, // 'id'를 명시적으로 추출합니다.

            'user_id' => \App\Models\User::role('dealer')->inRandomOrder()->first()->id, // 'id'를 명시적으로 추출

            // 'status' => $this->faker->randomElement(['ask', 'chosen']),
            'price' => $this->faker->numberBetween(1000, 5000),
        ];
    }
}
