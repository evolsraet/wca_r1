<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('사용자 회원가입 API가 정상 작동한다', function () {
    // When: 올바른 데이터로 회원가입을 요청하면
    $response = $this->postJson('/api/users', [
        'user' => [
            'name' => '홍길동',
            'email' => 'test@example.com',
            'phone' => '01012345678',
            'password' => 'password123!',
            'password_confirmation' => 'password123!',
            'isCheckPrivacy' => true
        ]
    ]);

    // Then: 성공적으로 처리된다
    $response->assertStatus(201)
        ->assertJson([
            'status' => 'success'
        ]);

    // And: 데이터베이스에 사용자가 생성된다
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'name' => '홍길동',
        'phone' => '01012345678'
    ]);
});

test('딜러 회원가입 API가 정상 작동한다', function () {
    // When: 딜러 정보를 포함하여 회원가입을 요청하면
    $response = $this->postJson('/api/users', [
        'user' => [
            'name' => '딜러홍길동',
            'email' => 'dealer@example.com',
            'phone' => '01087654321',
            'password' => 'password123!',
            'password_confirmation' => 'password123!',
            'role' => 'dealer',
            'isCheckPrivacy' => true
        ],
        'dealer' => [
            'name' => '딜러홍길동',
            'birthday' => '900101',
            'phone' => '01087654321',
            'company' => '테스트자동차',
            'company_duty' => '대표',
            'introduce' => '안녕하세요 딜러입니다',
            'company_post' => '12345',
            'company_addr1' => '서울시 강남구',
            'company_addr2' => '테헤란로 123',
            'receive_post' => '12345',
            'receive_addr1' => '서울시 강남구',
            'receive_addr2' => '테헤란로 456',
            'business_registration_number' => '123-45-67890',
            'corporation_registration_number' => '110111-1234567',
            'car_management_business_registration_number' => '서울-12345',
            'isCheckDealer1' => true,
            'isCheckDealer2' => true,
            'isCheckDealer3' => true,
            'isCheckDealer4' => true
        ]
    ]);

    // Then: 성공적으로 처리된다
    $response->assertStatus(201);

    // And: 사용자와 딜러 정보가 모두 생성된다
    $this->assertDatabaseHas('users', [
        'email' => 'dealer@example.com',
        'name' => '딜러홍길동'
    ]);

    $this->assertDatabaseHas('dealers', [
        'company' => '테스트자동차'
    ]);
});

test('중복된 이메일로 회원가입할 수 없다', function () {
    // Given: 이미 존재하는 사용자가 있을 때
    createUser(['email' => 'existing@example.com']);

    // When: 같은 이메일로 회원가입을 시도하면
    $response = $this->postJson('/api/users', [
        'user' => [
            'name' => '새사용자',
            'email' => 'existing@example.com',
            'phone' => '01099999999',
            'password' => 'password123!',
            'password_confirmation' => 'password123!',
            'isCheckPrivacy' => true
        ]
    ]);

    // Then: 유효성 검사 오류가 반환된다
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['user.email']);
});

test('중복된 전화번호로 회원가입할 수 없다', function () {
    // Given: 이미 존재하는 사용자가 있을 때
    createUser(['phone' => '01012345678']);

    // When: 같은 전화번호로 회원가입을 시도하면
    $response = $this->postJson('/api/users', [
        'user' => [
            'name' => '새사용자',
            'email' => 'new@example.com',
            'phone' => '01012345678',
            'password' => 'password123!',
            'password_confirmation' => 'password123!',
            'isCheckPrivacy' => true
        ]
    ]);

    // Then: 유효성 검사 오류가 반환된다
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['user.phone']);
});

test('필수 필드 누락 시 회원가입할 수 없다', function () {
    // When: 필수 필드가 누락된 채로 회원가입을 시도하면
    $response = $this->postJson('/api/users', [
        'user' => [
            'name' => '홍길동'
            // email, phone, password 누락
        ]
    ]);

    // Then: 유효성 검사 오류가 반환된다
    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'user.email',
            'user.phone',
            'user.password',
            'user.isCheckPrivacy'
        ]);
});

test('로그인 API가 정상 작동한다', function () {
    // Given: 사용자가 존재할 때
    $user = createUser([
        'email' => 'test@example.com',
        'password' => Hash::make('password123!')
    ]);

    // When: 올바른 자격증명으로 로그인하면
    $response = $this->postJson('/login', [
        'email' => 'test@example.com',
        'password' => 'password123!'
    ]);

    // Then: 성공적으로 인증된다
    $response->assertStatus(200);
    $this->assertAuthenticatedAs($user);
});

test('전화번호로 로그인할 수 있다', function () {
    // Given: 사용자가 존재할 때
    $user = createUser([
        'phone' => '01012345678',
        'password' => Hash::make('password123!')
    ]);

    // When: 전화번호로 로그인하면
    $response = $this->postJson('/login', [
        'email' => '01012345678',
        'password' => 'password123!'
    ]);

    // Then: 성공적으로 인증된다
    $response->assertStatus(200);
    $this->assertAuthenticatedAs($user);
});

test('잘못된 자격증명으로 로그인할 수 없다', function () {
    // Given: 사용자가 존재할 때
    createUser([
        'email' => 'test@example.com',
        'password' => Hash::make('password123!')
    ]);

    // When: 잘못된 비밀번호로 로그인하면
    $response = $this->postJson('/login', [
        'email' => 'test@example.com',
        'password' => 'wrongpassword'
    ]);

    // Then: 인증 실패한다
    $response->assertStatus(422);
    $this->assertGuest();
});

test('심사중인 사용자는 로그인할 수 없다', function () {
    // Given: 심사중인 사용자가 있을 때
    $user = createUser([
        'email' => 'test@example.com',
        'password' => Hash::make('password123!'),
        'status' => 'ask'
    ]);

    // When: 로그인을 시도하면
    $response = $this->postJson('/login', [
        'email' => 'test@example.com',
        'password' => 'password123!'
    ]);

    // Then: 로그인이 거부된다
    $response->assertStatus(422);
    $this->assertGuest();
});

test('사용자 정보 조회 API가 정상 작동한다', function () {
    // Given: 인증된 사용자가 있을 때
    $user = actingAsUser(['name' => '홍길동']);

    // When: 사용자 정보를 조회하면
    $response = $this->getJson('/api/users/me');

    // Then: 사용자 정보가 반환된다
    $response->assertStatus(200);
});

test('비인증 사용자는 사용자 정보를 조회할 수 없다', function () {
    // When: 비인증 상태에서 사용자 정보를 조회하면
    $response = $this->getJson('/api/users/me');

    // Then: 401 에러가 반환된다
    $response->assertStatus(401);
});