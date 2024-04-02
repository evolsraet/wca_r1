<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\bid;
use Tests\TestCase;
use App\Models\User;
use App\Models\Auction;
use Illuminate\Support\Facades\DB;
use Tests\Traits\NotSuccessfulTestTrait;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BidTest extends TestCase
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

    public function test_입찰생성(): void
    {
        $auction = Auction::where('status', 'ing')->first();
        $bid = Bid::factory([
            'auction_id' => $auction->id
        ])->make();

        $data = [
            'bid' => $bid->makeVisible($bid->getHidden())->toArray()
        ];

        // 로그인 role:user 사용자만
        $disableUser = User::role('user')->first();
        $this->actingAs($disableUser);
        $response = $this->postJson('/api/bids', $data);

        $this->assertNotSuccessful($response);


        // 정상작동
        $user = User::where('id', $data['bid']['user_id'])->first();
        $this->actingAs($user);
        $response = $this->postJson('/api/bids', $data);

        $response->assertSuccessful();
        $this->assertDatabaseHas('bids', [
            'user_id' => $user->id,
            'auction_id' => $data['bid']['auction_id'],
            'price' => $data['bid']['price'],
        ]);
    }

    public function test_입찰수정(): void
    {
        $user = User::has('bid')->first();
        $bid = Bid::where('user_id', $user->id)->first();

        $data = [
            'bid' => [
                'price' => 9999
            ]
        ];


        // 본인 or 관리자만
        $disableUser = User::role('dealer')->where('id', '!=', $user->id)->first();
        $this->actingAs($disableUser);
        $response = $this->putJson('/api/bids/' . $bid->id, $data);
        $this->assertNotSuccessful($response);

        // 정상작동
        $this->actingAs($user);
        $response = $this->putJson('/api/bids/' . $bid->id, $data);

        $response->assertSuccessful();
        $this->assertDatabaseHas('bids', [
            'user_id' => $user->id,
            'price' => $data['bid']['price'],
        ]);
    }

    public function test_입찰삭제(): void
    {
        $bid = Bid::first();
        $bidId = $bid->id;
        $user = User::where('id', $bid->user_id)->first();

        // 본인 or 관리자만
        $disableUser = User::role('dealer')->where('id', '!=', $user->id)->first();
        // dump([
        //     $user->id,
        //     $disableUser->id
        // ]);
        $this->actingAs($disableUser);
        $response = $this->deleteJson('/api/bids/' . $bid->id);
        $this->assertNotSuccessful($response);

        // 삭제
        $this->actingAs($user);
        // dd([
        //     auth()->user()->id,
        //     $user->id,
        //     $bid->user_id
        // ]);
        $response = $this->deleteJson('/api/bids/' . $bid->id);

        $response->assertSuccessful();
        // 직접 쿼리를 실행하여 `deleted_at`이 `null`이 아닌 레코드를 찾습니다.
        $deletedRecord = DB::table('bids')
            ->where('id', $bid->id)
            ->whereNotNull('deleted_at')
            ->first();

        // `deleted_at`이 `null`이 아닌 레코드가 존재하는지 확인합니다.
        $this->assertNotNull($deletedRecord, '소프트딜리트 확인 안됨');
    }
}
