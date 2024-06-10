<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Auction;
use Illuminate\Support\Facades\DB;
use Tests\Traits\NotSuccessfulTestTrait;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuctionTest extends TestCase
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

    public function test_경매생성(): void
    {
        $auction = Auction::factory()->make();
        $data = [
            'auction' => $auction->makeVisible($auction->getHidden())->toArray()
        ];
        $data['auction']['final_at'] = now()->addDay()->toDateTimeString();
        // 로그인 role:user 사용자만
        $user = User::role('dealer')->first();
        $this->actingAs($user);
        $response = $this->postJson('/api/auctions', $data);

        $this->assertNotSuccessful($response);

        // 정상작동
        $user = User::role('user')->first();
        $this->actingAs($user);
        $response = $this->postJson('/api/auctions', $data);

        // dd: 로그인된 사용자 배열, 응답배열 을 출력
        // dd([
        //     '로그인된 사용자' => $user->toArray(),
        //     '응답 배열' => $response->json()
        // ]);

        // 데이터 확인
        // $responsedata = $response->json('data');
        // $response->assertJson([
        //     'data' => [
        //         'owner_name' => 'Expected Name'
        //     ],
        // ]);
        $response->assertSuccessful();
        $this->assertDatabaseHas('auctions', [
            'user_id' => $user->id,
            'owner_name' => $data['auction']['owner_name'],
            'car_no' => $data['auction']['car_no'],
        ]);
    }

    public function test_경매수정(): void
    {
        $user = User::role('user')->has('auction')->first();
        // dd([
        //     $user->id,
        //     Auction::where('user_id', $user->id)->first()->toArray(),
        //     (DB::getQueryLog())
        // ]);
        $auction = Auction::where('user_id', $user->id)->first();

        $data = [
            'auction' => [
                'car_no' => 'updated car_no'
            ]
        ];

        // 본인 or 관리자만
        $disableUser = User::role('dealer')->first();
        $this->actingAs($disableUser);
        $response = $this->putJson('/api/auctions/' . $auction->id, $data);

        $this->assertNotSuccessful($response);

        // 정상작동
        $this->actingAs($user);
        $response = $this->putJson('/api/auctions/' . $auction->id, $data);

        $response->assertSuccessful();
        $this->assertDatabaseHas('auctions', [
            'user_id' => $user->id,
            'owner_name' => $auction->owner_name,
            'car_no' => $data['auction']['car_no'],
        ]);
    }

    public function test_경매삭제(): void
    {
        $auction = Auction::first();
        $user = User::where('id', $auction->user_id)->first();

        // 소프트삭제
        $this->actingAs($user);
        $response = $this->deleteJson('/api/auctions/' . $auction->id);

        $response->assertSuccessful();
        // $this->assertDatabaseHas('auctions', [
        //     'id' => $auction->id,
        //     'deleted_at' => $auction->owner_name,
        // ]);

        // 직접 쿼리를 실행하여 `deleted_at`이 `null`이 아닌 레코드를 찾습니다.
        $auctionRecord = DB::table('auctions')
            ->where('id', $auction->id)
            ->whereNotNull('deleted_at')
            ->first();

        // `deleted_at`이 `null`이 아닌 레코드가 존재하는지 확인합니다.
        $this->assertNotNull($auctionRecord, '소프트딜리트 확인 안됨');
    }
}
