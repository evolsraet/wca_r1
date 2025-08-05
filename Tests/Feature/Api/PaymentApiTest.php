<?php

use App\Models\Auction;
use App\Models\Payment;

test('결제 폼 조회 API가 정상 작동한다', function () {
    // Given: 인증된 사용자가 있을 때
    $user = actingAsUser();

    // When: 결제 폼을 조회하면
    $response = $this->getJson('/api/payment');

    // Then: 결제 폼이 반환된다
    $response->assertStatus(200);
});

test('특정 결제 상세 정보를 조회할 수 있다', function () {
    // Given: 사용자의 결제가 있을 때
    $user = actingAsUser();
    $auction = Auction::factory()->create();
    $payment = Payment::factory()->create([
        'user_id' => $user->id,
        'auction_id' => $auction->id
    ]);

    // When: 특정 결제 정보를 조회하면
    $response = $this->getJson("/api/payments/{$payment->id}");

    // Then: 결제 상세 정보가 반환된다
    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $payment->id,
                'amount' => $payment->amount
            ]
        ]);
});

test('다른 사용자의 결제 정보는 조회할 수 없다', function () {
    // Given: 다른 사용자의 결제가 있을 때
    $currentUser = actingAsUser();
    $otherUser = createUser();
    $auction = Auction::factory()->create();
    $payment = Payment::factory()->create([
        'user_id' => $otherUser->id,
        'auction_id' => $auction->id
    ]);

    // When: 다른 사용자의 결제 정보를 조회하면
    $response = $this->getJson("/api/payments/{$payment->id}");

    // Then: 접근이 거부된다
    $response->assertStatus(403);
});

test('결제 생성 API가 정상 작동한다', function () {
    // Given: 인증된 사용자와 경매가 있을 때
    $user = actingAsUser();
    $auction = Auction::factory()->create([
        'status' => 'completed',
        'winner_id' => $user->id,
        'current_price' => 150000
    ]);

    // When: 결제를 생성하면
    $response = $this->postJson('/api/payments', [
        'auction_id' => $auction->id,
        'payment_method' => 'card',
        'amount' => 150000
    ]);

    // Then: 결제가 성공적으로 생성된다
    $response->assertStatus(201)
        ->assertJson([
            'status' => 'success'
        ]);

    $this->assertDatabaseHas('payments', [
        'user_id' => $user->id,
        'auction_id' => $auction->id,
        'amount' => 150000
    ]);
});

test('낙찰자가 아닌 사용자는 결제할 수 없다', function () {
    // Given: 다른 사용자가 낙찰된 경매가 있을 때
    $user = actingAsUser();
    $winner = createUser();
    $auction = Auction::factory()->create([
        'status' => 'completed',
        'winner_id' => $winner->id
    ]);

    // When: 낙찰자가 아닌 사용자가 결제를 시도하면
    $response = $this->postJson('/api/payments', [
        'auction_id' => $auction->id,
        'payment_method' => 'card',
        'amount' => 150000
    ]);

    // Then: 결제가 거부된다
    $response->assertStatus(403);
});

test('이미 결제된 경매는 중복 결제할 수 없다', function () {
    // Given: 이미 결제된 경매가 있을 때
    $user = actingAsUser();
    $auction = Auction::factory()->create([
        'status' => 'completed',
        'winner_id' => $user->id
    ]);
    
    Payment::factory()->create([
        'user_id' => $user->id,
        'auction_id' => $auction->id,
        'status' => 'completed'
    ]);

    // When: 같은 경매에 대해 다시 결제를 시도하면
    $response = $this->postJson('/api/payments', [
        'auction_id' => $auction->id,
        'payment_method' => 'card',
        'amount' => 150000
    ]);

    // Then: 결제가 거부된다
    $response->assertStatus(422);
});