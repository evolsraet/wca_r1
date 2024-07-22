<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Board>
 */
class BoardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => null,
            'categories' => json_encode($this->faker->words(3)),
            'list_permission' => null,
            'show_permission' => null,
            'write_permission' => null,
            'reply_permission' => null,
            'comment_permission' => null,
            'attach_permission' => null,
            'paginate' => 10,
            'skin' => $this->faker->randomElement(['default']),
            'admins' => null
        ];
    }
}
