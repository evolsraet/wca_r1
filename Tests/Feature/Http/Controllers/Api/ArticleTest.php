<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Board;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Traits\NotSuccessfulTestTrait;

class ArticleTest extends TestCase
{
    use RefreshDatabase, WithFaker, NotSuccessfulTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_게시글_목록_조회(): void
    {
        $board = Board::first();
        // die("[{$board->id}]");
        $response = $this->getJson("/api/board/{$board->id}/articles");
        // print_r($response->json());
        $response->assertJson(['status' => 'ok']);
    }

    public function test_게시글_생성(): void
    {
        $user = User::Role('admin')->first();
        $board = Board::first();
        $articleData = [
            'article' => [
                'title' => 'article title',
                'content' => 'article content',
            ],
        ];

        $response = $this->actingAs($user)
            ->postJson("/api/board/{$board->id}/articles", $articleData);

        // print_r($response->json());
        // die();

        $response->assertSuccessful();

        $this->assertDatabaseHas('articles', [
            'title' => $articleData['article']['title'],
            'content' => $articleData['article']['content'],
            'user_id' => $user->id,
            'board_id' => $board->id,
        ]);
    }

    public function test_게시글_조회(): void
    {
        $article = Article::factory()->create();

        $response = $this->getJson("/api/board/{$article->board_id}/articles/{$article->id}");
        // print_r($response->json());
        // die();
        $response
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $article->id,
                    'title' => $article->title,
                    'content' => $article->content,
                    'user_id' => $article->user_id,
                    'board_id' => $article->board_id,
                ]
            ]);
    }

    public function test_게시글_수정(): void
    {
        $user = User::Role('admin')->first();
        $article = Article::factory()->create(['user_id' => $user->id]);
        $updateData = [
            'article' => [
                'title' => $this->faker->sentence,
                'content' => $this->faker->paragraph,
            ],
        ];

        $response = $this->actingAs($user)
            ->putJson("/api/board/{$article->board_id}/articles/{$article->id}", $updateData);

        // print_r($response->json());
        // die();

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $article->id,
                    'title' => $updateData['article']['title'],
                    'content' => $updateData['article']['content'],
                ]
            ]);

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => $updateData['article']['title'],
            'content' => $updateData['article']['content'],
        ]);
    }

    public function test_게시글_삭제(): void
    {
        $user = User::Role('admin')->first();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/board/{$article->board_id}/articles/{$article->id}");

        $response->assertSuccessful();
        $this->assertSoftDeleted('articles', ['id' => $article->id]);
    }

    public function test_권한없는_사용자_게시글_수정_실패(): void
    {
        $user = User::Role('admin')->first();
        $otherUser = User::Role('user')->first();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $updateData = [
            'article' => [
                'title' => $this->faker->sentence,
                'content' => $this->faker->paragraph,
            ],
        ];

        $response = $this->actingAs($otherUser)
            ->putJson("/api/board/notice/articles/{$article->id}", $updateData);


        $this->assertNotSuccessful($response);
    }

    public function test_권한없는_사용자_게시글_삭제_실패(): void
    {
        $user = User::Role('admin')->first();
        $otherUser = User::Role('user')->first();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($otherUser)
            ->deleteJson("/api/board/notice/articles/{$article->id}");

        $this->assertNotSuccessful($response);
    }
}
