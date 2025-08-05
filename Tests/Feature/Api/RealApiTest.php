<?php

use App\Models\User;
use App\Models\Auction;
use Illuminate\Support\Facades\Hash;

// API 테스트 - 실제 요청 형식 기반

test('회원가입 API 테스트 - 정상 데이터', function () {
    $response = $this->post('/api/users', [
        'user' => [
            'name' => '테스트사용자',
            'email' => 'api_test@example.com',
            'phone' => '01087654321',
            'password' => 'password123!',
            'password_confirmation' => 'password123!',
            'isCheckPrivacy' => 1
        ]
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'status' => 'ok',
            'message' => '정상처리 되었습니다.'
        ]);
    
    $this->assertDatabaseHas('users', [
        'email' => 'api_test@example.com',
        'name' => '테스트사용자'
    ]);
});

test('회원가입 API 테스트 - 중복 이메일', function () {
    // 먼저 사용자 생성
    createUser(['email' => 'duplicate@example.com']);
    
    $response = $this->post('/api/users', [
        'user' => [
            'name' => '중복사용자',
            'email' => 'duplicate@example.com',
            'phone' => '01011111111',
            'password' => 'password123!',
            'password_confirmation' => 'password123!',
            'isCheckPrivacy' => 1
        ]
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['user.email']);
});

test('회원가입 API 테스트 - 필수 필드 누락', function () {
    $response = $this->post('/api/users', [
        'user' => [
            'name' => '테스트사용자'
            // email, phone, password 누락
        ]
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'user.email',
            'user.phone', 
            'user.password',
            'user.isCheckPrivacy'
        ]);
});

test('로그인 API 테스트 - 이메일 로그인', function () {
    $user = createUser([
        'email' => 'login_test@example.com',
        'password' => Hash::make('password123!')
    ]);

    $response = $this->post('/login', [
        'email' => 'login_test@example.com',
        'password' => 'password123!'
    ]);

    $response->assertStatus(200);
    $this->assertAuthenticatedAs($user);
});

test('로그인 API 테스트 - 전화번호 로그인', function () {
    $user = createUser([
        'phone' => '01055555555',
        'password' => Hash::make('password123!')
    ]);

    $response = $this->post('/login', [
        'email' => '01055555555', // phone을 email 필드로 전송
        'password' => 'password123!'
    ]);

    $response->assertStatus(200);
    $this->assertAuthenticatedAs($user);
});

test('로그인 API 테스트 - 잘못된 비밀번호', function () {
    createUser([
        'email' => 'wrong_pw@example.com',
        'password' => Hash::make('password123!')
    ]);

    $response = $this->post('/login', [
        'email' => 'wrong_pw@example.com',
        'password' => 'wrongpassword'
    ]);

    $response->assertStatus(422);
    $this->assertGuest();
});

test('사용자 정보 조회 API 테스트', function () {
    $user = actingAsUser(['name' => 'API테스트사용자']);

    $response = $this->get('/api/users/me');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'name',
            'email',
            'phone'
        ]);
});

test('경매 목록 조회 API 테스트 - 인증된 사용자', function () {
    $user = actingAsUser();
    
    // 테스트용 경매 생성
    Auction::factory()->count(3)->create([
        'status' => 'progress'
    ]);

    $response = $this->get('/api/auctions');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'status'
                ]
            ]
        ]);
});

test('경매 목록 조회 API 테스트 - 비인증 사용자', function () {
    $response = $this->get('/api/auctions');

    $response->assertStatus(401);
});

test('입찰 API 테스트 - 정상 입찰', function () {
    $user = actingAsUser();
    $auction = Auction::factory()->create([
        'status' => 'progress',
        'start_price' => 100000,
        'current_price' => 100000,
        'seller_id' => createUser()->id // 다른 사용자가 등록한 경매
    ]);

    $response = $this->post('/api/bids', [
        'auction_id' => $auction->id,
        'bid_amount' => 110000
    ]);

    $response->assertStatus(200);
    
    $this->assertDatabaseHas('bids', [
        'auction_id' => $auction->id,
        'user_id' => $user->id,
        'bid_amount' => 110000
    ]);
});

test('입찰 API 테스트 - 비인증 사용자', function () {
    $auction = Auction::factory()->create(['status' => 'progress']);

    $response = $this->post('/api/bids', [
        'auction_id' => $auction->id,
        'bid_amount' => 110000
    ]);

    $response->assertStatus(401);
});

test('결제 폼 조회 API 테스트', function () {
    $user = actingAsUser();

    $response = $this->get('/api/payment');

    $response->assertStatus(200);
});

test('비인증 사용자 결제 폼 접근 차단', function () {
    $response = $this->get('/api/payment');

    $response->assertStatus(401);
});