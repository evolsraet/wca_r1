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
        $status = $this->faker->randomElement(array_keys((new Auction)->enums['status']));

        $result = [
            'user_id' => $user->id,
            'owner_name' => $this->faker->name,
            'car_no' => $this->faker->bothify('??###'),
            'status' => $status,
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

        if ($status === 'chosen') {
            $result['choice_at'] = $this->faker->dateTimeBetween('-1 month', '+1 month');
        }

        return $result;
    }
}
