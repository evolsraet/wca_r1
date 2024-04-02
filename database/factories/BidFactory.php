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
            'auction_id' => \App\Models\Auction::inRandomOrder()->first(),
            'user_id' => \App\Models\User::role('dealer')->inRandomOrder()->first(),
            // 'status' => $this->faker->randomElement(['ask', 'chosen']),
            'price' => $this->faker->numberBetween(1000, 5000),
        ];
    }
}
