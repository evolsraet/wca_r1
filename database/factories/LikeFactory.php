<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Auction;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Like; // Ensure your model namespace is correct, considering the renaming

class LikeFactory extends Factory
{
    protected $model = Like::class;

    public function definition(): array
    {
        return [
            'likeable_type' => Auction::class,
            'likeable_id' => Auction::inRandomOrder()->first(),
            'user_id' => User::role('dealer')->inRandomOrder()->first(),
        ];
    }
}
