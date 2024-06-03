<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Review;
use App\Models\Auction;
use Illuminate\Support\Facades\DB;
use Tests\Traits\NotSuccessfulTestTrait;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReviewTest extends TestCase
{
    use RefreshDatabase;
    // use DatabaseTransactions;
    use WithFaker;
    use NotSuccessfulTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');

        DB::enableQueryLog();
    }

    public function test_생성(): void
    {
        $auction = Auction::where('status', 'done')->doesntHave('reviews')->first();

        $review = Review::factory([
            'auction_id' => $auction->id
        ])->make();

        $data = [
            'review' => $review->makeVisible($review->getHidden())->toArray()
        ];

        // 로그인 작성 사용자만
        $disableUser = User::role('user')->where('id', '!=', $auction->user_id)->first();
        $this->actingAs($disableUser);
        $response = $this->postJson('/api/reviews', $data);
        $this->assertNotSuccessful($response);


        // 정상작동
        $user = User::find($auction->user_id);
        $this->actingAs($user);

        // dd([
        //     $auction->toArray(),
        //     $user->toArray(),
        //     $auction->user_id,
        //     auth()->user()->id,
        // ]);

        $response = $this->postJson('/api/reviews', $data);

        $response->assertSuccessful();
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'auction_id' => $auction->id,
        ]);
    }

    public function test_수정(): void
    {
        // factory를 이용해 데이터를 추가하는 코드 작성해줘
        $review = Review::factory()->create();
        $user = User::where('id', $review->user_id)->first();

        $data = [
            'review' => [
                'content' => 'updated'
            ]
        ];

        // 본인 or 관리자만
        $disableUser = User::role('user')->where('id', '!=', $user->id)->first();
        $this->actingAs($disableUser);

        // dd([$disableUser->id, $review->user_id]);

        $response = $this->putJson('/api/reviews/' . $review->id, $data);
        $this->assertNotSuccessful($response);

        // dd([
        //     $review->toArray(),
        //     $disableUser->toArray(),
        //     auth()->user()->id
        // ]);

        // 정상작동
        $this->actingAs($user);
        $response = $this->putJson('/api/reviews/' . $review->id, $data);
        $response->assertSuccessful();
        $this->assertDatabaseHas('reviews', [
            'user_id' => $review->user_id,
            'auction_id' => $review->auction_id,
            'content' => 'updated',
        ]);
    }

    public function test_삭제(): void
    {
        $review = Review::factory()->create();
        $user = User::where('id', $review->user_id)->first();

        // 실패 : 본인 or 관리자만
        $disableUser = User::role('user')->where('id', '!=', $user->id)->first();
        $this->actingAs($disableUser);
        $response = $this->deleteJson('/api/reviews/' . $review->id);
        $this->assertNotSuccessful($response);

        // 삭제
        $this->actingAs($user);
        $response = $this->deleteJson('/api/reviews/' . $review->id);

        $response->assertSuccessful();
        // 직접 쿼리를 실행하여 `deleted_at`이 `null`이 아닌 레코드를 찾습니다.
        $deletedRecord = DB::table('reviews')
            ->where('id', $review->id)
            ->whereNotNull('deleted_at')
            ->first();

        // `deleted_at`이 `null`이 아닌 레코드가 존재하는지 확인합니다.
        $this->assertNotNull($deletedRecord, '소프트딜리트 확인 안됨');
    }
}
