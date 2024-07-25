<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Addressbook>
 */
class AddressbookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = \App\Models\User::role('dealer')->inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'name' => $this->faker->name,
            'addr_post' => $this->faker->postcode,
            'addr1' => $this->faker->address,
            'addr2' => $this->faker->numerify('#####'),
        ];
    }
}
