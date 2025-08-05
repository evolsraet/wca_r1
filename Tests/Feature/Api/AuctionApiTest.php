<?php

use App\Models\Auction;
use App\Models\User;
use App\Models\Bid;

test('경매 목록을 조회할 수 있다', function () {
    // Given: 경매 데이터가 있을 때
    $auctions = Auction::factory()->count(5)->create([
        'status' => 'progress'
    ]);

    // When: 경매 목록을 요청하면
    $response = $this->getJson('/api/auctions');

    // Then: 성공적으로 응답한다
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'status',
                    'start_price',
                    'current_price',
                    'created_at'
                ]
            ]
        ]);
});

test('진행중인 경매만 필터링할 수 있다', function () {
    // Given: 다양한 상태의 경매가 있을 때
    Auction::factory()->create(['status' => 'progress']);
    Auction::factory()->create(['status' => 'completed']);
    Auction::factory()->create(['status' => 'pending']);

    // When: 진행중인 경매만 조회하면
    $response = $this->getJson('/api/auctions?status=progress');

    // Then: 진행중인 경매만 반환된다
    $response->assertStatus(200);
    
    $auctions = $response->json('data');
    foreach ($auctions as $auction) {
        expect($auction['status'])->toBe('progress');
    }
});

test('인증된 사용자는 경매를 생성할 수 있다', function () {
    // Given: 인증된 사용자가 있을 때
    $user = actingAsUser();

    // When: 경매 생성을 요청하면
    $auctionData = [
        'title' => '테스트 경매',
        'description' => '테스트 경매 설명',
        'start_price' => 100000,
        'car_model' => '현대 소나타',
        'car_year' => 2020,
        'car_mileage' => 50000
    ];

    $response = $this->postJson('/api/auctions', $auctionData);

    // Then: 경매가 성공적으로 생성된다
    $response->assertStatus(201)
        ->assertJson([
            'status' => 'success',
            'data' => [
                'title' => '테스트 경매',
                'seller_id' => $user->id
            ]
        ]);

    $this->assertDatabaseHas('auctions', [
        'title' => '테스트 경매',
        'seller_id' => $user->id
    ]);
});

test('비인증 사용자는 경매를 생성할 수 없다', function () {
    // Given: 비인증 상태일 때

    // When: 경매 생성을 요청하면
    $response = $this->postJson('/api/auctions', [
        'title' => '테스트 경매'
    ]);

    // Then: 401 에러가 반환된다
    $response->assertStatus(401);
});

test('경매 상세 정보를 조회할 수 있다', function () {
    // Given: 경매가 있을 때
    $auction = Auction::factory()->create();

    // When: 경매 상세 정보를 요청하면
    $response = $this->getJson("/api/auctions/{$auction->id}");

    // Then: 상세 정보가 반환된다
    $response->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'data' => [
                'id' => $auction->id,
                'title' => $auction->title
            ]
        ]);
});

test('존재하지 않는 경매 조회 시 404 에러가 반환된다', function () {
    // When: 존재하지 않는 경매를 요청하면
    $response = $this->getJson('/api/auctions/99999');

    // Then: 404 에러가 반환된다
    $response->assertStatus(404);
});

test('인증된 사용자는 경매에 입찰할 수 있다', function () {
    // Given: 인증된 사용자와 진행중인 경매가 있을 때
    $user = actingAsUser();
    $auction = Auction::factory()->create([
        'status' => 'progress',
        'start_price' => 100000,
        'current_price' => 100000
    ]);

    // When: 입찰을 요청하면
    $response = $this->postJson('/api/bids', [
        'auction_id' => $auction->id,
        'bid_amount' => 110000
    ]);

    // Then: 입찰이 성공적으로 처리된다
    $response->assertStatus(201)
        ->assertJson([
            'status' => 'success'
        ]);

    $this->assertDatabaseHas('bids', [
        'auction_id' => $auction->id,
        'user_id' => $user->id,
        'bid_amount' => 110000
    ]);
});

test('현재가보다 낮은 금액으로 입찰할 수 없다', function () {
    // Given: 진행중인 경매가 있을 때
    $user = actingAsUser();
    $auction = Auction::factory()->create([
        'status' => 'progress',
        'current_price' => 150000
    ]);

    // When: 현재가보다 낮은 금액으로 입찰하면
    $response = $this->postJson('/api/bids', [
        'auction_id' => $auction->id,
        'bid_amount' => 140000
    ]);

    // Then: 유효성 검사 오류가 반환된다
    $response->assertStatus(422);
});

test('자신의 경매에는 입찰할 수 없다', function () {
    // Given: 자신이 등록한 경매가 있을 때
    $user = actingAsUser();
    $auction = Auction::factory()->create([
        'seller_id' => $user->id,
        'status' => 'progress'
    ]);

    // When: 자신의 경매에 입찰하면
    $response = $this->postJson('/api/bids', [
        'auction_id' => $auction->id,
        'bid_amount' => 110000
    ]);

    // Then: 오류가 반환된다
    $response->assertStatus(422);
});