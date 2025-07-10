<?php

namespace Tests\Feature;

use App\Models\Bid;
use Tests\TestCase;
use App\Models\Auction;
use Illuminate\Support\Facades\DB;
use Tests\Traits\NotSuccessfulTestTrait;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CrudTest extends TestCase
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

    public function test_인덱스_서브_whereIn(): void
    {
        $this->withoutExceptionHandling();

        $user = \App\Models\User::role('user')->firstOrCreate();
        $dealer = \App\Models\User::role('dealer')->firstOrCreate();

        $this->actingAs($user);

        // 'wait' 또는 'done' 상태의 auction을 가진 bids 생성
        $auctionWait = Auction::factory()->create(['user_id' => $user->id, 'status' => 'wait']);
        $auctionDone = Auction::factory()->create(['user_id' => $user->id, 'status' => 'done']);
        $auctionOther = Auction::factory()->create(['user_id' => $dealer->id, 'status' => 'ing']); // 이 상태는 포함되지 않아야 함

        $this->actingAs($dealer);

        $bidInWait = Bid::factory()->create(['user_id' => $dealer->id, 'auction_id' => $auctionWait->id]);
        $bidInDone = Bid::factory()->create(['user_id' => $dealer->id, 'auction_id' => $auctionDone->id]);
        $bidInOther = Bid::factory()->create(['user_id' => $dealer->id, 'auction_id' => $auctionOther->id]);

        $this->actingAs($user);

        // API 요청
        $response = $this->getJson('/api/bids?with=auction&where=auction.status:whereIn:done,wait');

        // // 각 auction의 status 출력
        // foreach ($response->json('data') as $item) {
        //     dump($item['auction']['status']);
        // }

        // 응답 검증
        $response->assertSuccessful();
        // data[]->auction.status 가 wait, done 인지 확인
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json
                ->has(
                    'data',
                    fn (AssertableJson $arr) =>
                    $arr->each(
                        fn (AssertableJson $item) =>
                        $item->where('auction.status', fn ($status) => in_array($status, ['wait', 'done']))
                            ->etc()  // This ensures that only the fields specified in the test are expected.
                    )
                )->etc()
        );
    }
}
