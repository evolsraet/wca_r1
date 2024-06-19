<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\Like;
use App\Models\User;
use App\Models\Auction;
use Illuminate\Support\Facades\DB;
use Tests\Traits\NotSuccessfulTestTrait;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LikeTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use NotSuccessfulTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');

        DB::enableQueryLog();
    }


    public function test_좋아요_삽입_테스트(): void
    {
        $auction = Auction::inRandomOrder()->first();
        $user = User::factory()->create();

        $like = Like::factory()->make([
            'likeable_id' => $auction->id,
            'user_id' => $user->id
        ]);
        $data = [
            'like' => $like->toArray()
        ];

        $this->actingAs($user);
        $response = $this->postJson('/api/likes', $data);
        $response->assertJsonPath('data.user_id', $user->id);

        // 비회원 삽입 시도
        $response = $this->postJson('/api/likes', $data);
        $this->assertNotSuccessful($response);
    }

    public function test_좋아요_업데이트_테스트(): void
    {
        $like = Like::factory()->create();
        $data = ['updated_at' => now()];

        // 일반 사용자가 다른 사용자의 좋아요를 수정 시도
        $otherUser = User::factory()->create();
        $this->actingAs($otherUser);
        $response = $this->putJson("/api/likes/{$like->id}", $data);
        $this->assertNotSuccessful($response);

        // 본인이 수정 시도
        $this->actingAs($like->user);
        $response = $this->putJson("/api/likes/{$like->id}", $data);
        $response->assertOk();

        // 관리자가 수정 시도
        $admin = User::role('admin')->first();
        $this->actingAs($admin);
        $response = $this->putJson("/api/likes/{$like->id}", $data);
        $response->assertOk();
    }

    public function test_좋아요_삭제_테스트(): void
    {
        $like = Like::factory()->create();

        // 일반 사용자가 다른 사용자의 좋아요를 삭제 시도
        $otherUser = User::factory()->create();
        $this->actingAs($otherUser);
        $response = $this->deleteJson("/api/likes/{$like->id}");
        $this->assertNotSuccessful($response);

        // 본인이 삭제 시도
        $this->actingAs($like->user);
        $response = $this->deleteJson("/api/likes/{$like->id}");
        $response->assertOk();

        // 관리자가 삭제 시도
        $like = Like::factory()->create();

        $admin = User::role('admin')->first();
        $this->actingAs($admin);
        $response = $this->deleteJson("/api/likes/{$like->id}");
        $response->assertOk();
    }
}
