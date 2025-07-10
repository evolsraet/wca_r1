<?php

use App\Models\User;
use App\Jobs\ResetPasswordLinkJob;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

beforeEach(function () {
    Queue::fake();
});

test('비밀번호 재설정 페이지에 접속할 수 있다', function () {
    $response = $this->get('/v2/forgot-password');
    
    $response->assertStatus(200)
        ->assertViewIs('v2.auth.forgot-password');
});

test('전화번호로 비밀번호 재설정 링크를 요청할 수 있다', function () {
    $user = User::factory()->create([
        'phone' => '01012345678',
        'email' => 'test@example.com'
    ]);
    
    $response = $this->post('/api/users/reset-password-link', [
        'phone' => '01012345678'
    ]);
    
    $response->assertStatus(200)
        ->assertJson([
            'message' => '비밀번호 재설정 링크가 SMS로 발송되었습니다.'
        ]);
    
    Queue::assertPushed(ResetPasswordLinkJob::class, function ($job) use ($user) {
        return $job->user->id === $user->id;
    });
});

test('존재하지 않는 전화번호로는 재설정 링크를 요청할 수 없다', function () {
    $response = $this->post('/api/users/reset-password-link', [
        'phone' => '01099999999'
    ]);
    
    $response->assertStatus(404)
        ->assertJson([
            'message' => '해당 전화번호로 등록된 사용자를 찾을 수 없습니다.'
        ]);
    
    Queue::assertNotPushed(ResetPasswordLinkJob::class);
});

test('유효한 토큰으로 비밀번호 재설정 페이지에 접속할 수 있다', function () {
    $user = User::factory()->create();
    
    $tokenData = [
        'user_id' => $user->id,
        'expires_at' => Carbon::now()->addHours(3)->timestamp
    ];
    
    $token = Crypt::encryptString(json_encode($tokenData));
    
    $response = $this->get("/v2/reset-password/{$token}");
    
    $response->assertStatus(200)
        ->assertViewIs('v2.auth.reset-password')
        ->assertViewHas('token', $token);
});

test('만료된 토큰으로는 접속할 수 없다', function () {
    $user = User::factory()->create();
    
    $tokenData = [
        'user_id' => $user->id,
        'expires_at' => Carbon::now()->subHour()->timestamp
    ];
    
    $token = Crypt::encryptString(json_encode($tokenData));
    
    $response = $this->get("/v2/reset-password/{$token}");
    
    $response->assertRedirect('/v2/forgot-password')
        ->assertSessionHas('error', '토큰이 만료되었습니다.');
});

test('비밀번호를 재설정할 수 있다', function () {
    $user = User::factory()->create();
    
    $tokenData = [
        'user_id' => $user->id,
        'expires_at' => Carbon::now()->addHours(3)->timestamp
    ];
    
    $token = Crypt::encryptString(json_encode($tokenData));
    
    $response = $this->post('/v2/reset-password', [
        'token' => $token,
        'password' => 'newpassword123!',
        'password_confirmation' => 'newpassword123!'
    ]);
    
    $response->assertRedirect('/v2/login')
        ->assertSessionHas('success', '비밀번호가 재설정되었습니다.');
    
    $user->refresh();
    expect(password_verify('newpassword123!', $user->password))->toBeTrue();
});

test('잘못된 토큰으로는 비밀번호를 재설정할 수 없다', function () {
    $response = $this->post('/v2/reset-password', [
        'token' => 'invalid-token',
        'password' => 'newpassword123!',
        'password_confirmation' => 'newpassword123!'
    ]);
    
    $response->assertRedirect('/v2/forgot-password')
        ->assertSessionHas('error');
});