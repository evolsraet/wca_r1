<?php

namespace Database\Factories;

use App\Models\Auction;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuctionFactory extends Factory
{
    protected $model = Auction::class;

    public function definition(): array
    {
        $user = \App\Models\User::role('user')->inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'owner_name' => $this->faker->name,
            'car_no' => $this->faker->bothify('??###'),
            'status' => $this->faker->randomElement(array_keys((new Auction)->enums['status'])),
            'region' => $this->faker->word,
            'addr_post' => $this->faker->postcode,
            'addr1' => $this->faker->address,
            'addr2' => '112',
            'bank' => $this->faker->word,
            'account' => $this->faker->bankAccountNumber,
            'memo' => $this->faker->text,
            'verified' => $this->faker->boolean,
            'hit' => $this->faker->randomDigit,
        ];
    }
}
