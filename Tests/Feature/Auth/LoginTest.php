<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('로그인 페이지에 접속할 수 있다', function () {
    $response = $this->get('/v2/login');
    
    $response->assertStatus(200)
        ->assertViewIs('v2.auth.login');
});

test('이메일과 비밀번호로 로그인할 수 있다', function () {
    $user = createUser([
        'email' => 'test@example.com',
        'password' => Hash::make('password')
    ]);
    
    $response = $this->post('/v2/login', [
        'email' => 'test@example.com',
        'password' => 'password'
    ]);
    
    $response->assertRedirect('/v2');
    $this->assertAuthenticatedAs($user);
});

test('전화번호와 비밀번호로 로그인할 수 있다', function () {
    $user = createUser([
        'phone' => '01012345678',
        'password' => Hash::make('password')
    ]);
    
    $response = $this->post('/v2/login', [
        'email' => '01012345678',
        'password' => 'password'
    ]);
    
    $response->assertRedirect('/v2');
    $this->assertAuthenticatedAs($user);
});

test('심사중 상태의 사용자는 로그인할 수 없다', function () {
    $user = createUser([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
        'status' => 'ask'
    ]);
    
    $response = $this->post('/v2/login', [
        'email' => 'test@example.com',
        'password' => 'password'
    ]);
    
    $response->assertRedirect()
        ->assertSessionHas('error', '현재 심사중입니다.');
    $this->assertGuest();
});

test('경고 상태의 사용자는 로그인할 수 없다', function ($status, $message) {
    $user = createUser([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
        'status' => $status
    ]);
    
    $response = $this->post('/v2/login', [
        'email' => 'test@example.com',
        'password' => 'password'
    ]);
    
    $response->assertRedirect()
        ->assertSessionHas('error', $message);
    $this->assertGuest();
})->with([
    ['warning1', '3일 이용금지'],
    ['warning2', '30일 이용금지'],
    ['expulsion', '영구정지']
]);

test('잘못된 비밀번호로 로그인할 수 없다', function () {
    $user = createUser([
        'email' => 'test@example.com',
        'password' => Hash::make('password')
    ]);
    
    $response = $this->post('/v2/login', [
        'email' => 'test@example.com',
        'password' => 'wrong-password'
    ]);
    
    $response->assertRedirect();
    $this->assertGuest();
});