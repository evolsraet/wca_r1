<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Board;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'board_id' => (string) Board::inRandomOrder()->first()->id,
            'title' => fake()->realText(10),
            'content' => fake()->realText(),
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
