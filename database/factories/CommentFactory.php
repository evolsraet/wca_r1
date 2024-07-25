<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        $article = Article::inRandomOrder()->first();

        return [
            'commentable_type' => Article::class,
            'commentable_id' => $article->id,
            'user_id' => $user->id,
            'content' => $this->faker->paragraph,
        ];
    }
}
