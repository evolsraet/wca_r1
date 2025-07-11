<?php

use App\Models\User;
use App\Jobs\UserStartJob;
use Illuminate\Support\Facades\Queue;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    Queue::fake();
});

test('회원가입 페이지에 접속할 수 있다', function () {
    $response = $this->get('/v2/register');
    
    $response->assertStatus(200)
        ->assertViewIs('v2.auth.register');
});

test('일반 사용자로 회원가입할 수 있다', function () {
    $response = $this->post('/api/users', [
        'name' => '홍길동',
        'phone' => '01012345678',
        'email' => 'test@example.com',
        'password' => 'password123!',
        'password_confirmation' => 'password123!',
        'user_type' => 'user',
        'agreement' => '1'
    ]);
    
    $response->assertStatus(201)
        ->assertJson([
            'message' => '회원가입이 완료되었습니다.'
        ]);
    
    $user = User::where('email', 'test@example.com')->first();
    
    expect($user)->not->toBeNull();
    expect($user->name)->toBe('홍길동');
    expect($user->phone)->toBe('01012345678');
    expect($user->status)->toBe('ask');
    expect($user->hasRole('user'))->toBeTrue();
    
    Queue::assertPushed(UserStartJob::class);
});

test('딜러로 회원가입할 수 있다', function () {
    $response = $this->post('/api/users', [
        'name' => '홍길동',
        'phone' => '01012345678',
        'email' => 'dealer@example.com',
        'password' => 'password123!',
        'password_confirmation' => 'password123!',
        'user_type' => 'dealer',
        'company_name' => '테스트 자동차',
        'business_number' => '123-45-67890',
        'representative_name' => '홍길동',
        'address' => '서울시 강남구',
        'agreement' => '1',
        'photo' => UploadedFile::fake()->image('photo.jpg'),
        'business_registration' => UploadedFile::fake()->create('business.pdf'),
        'seal_certificate' => UploadedFile::fake()->create('seal.pdf'),
        'attorney_letter' => UploadedFile::fake()->create('attorney.pdf')
    ]);
    
    $response->assertStatus(201)
        ->assertJson([
            'message' => '회원가입이 완료되었습니다.'
        ]);
    
    $user = User::where('email', 'dealer@example.com')->first();
    
    expect($user)->not->toBeNull();
    expect($user->hasRole('dealer'))->toBeTrue();
    expect($user->getMedia('photo')->count())->toBe(1);
    expect($user->getMedia('business_registration')->count())->toBe(1);
    
    Queue::assertPushed(UserStartJob::class);
});

test('중복된 이메일로 회원가입할 수 없다', function () {
    createUser(['email' => 'test@example.com']);
    
    $response = $this->post('/api/users', [
        'name' => '홍길동',
        'phone' => '01099999999',
        'email' => 'test@example.com',
        'password' => 'password123!',
        'password_confirmation' => 'password123!',
        'user_type' => 'user',
        'agreement' => '1'
    ]);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('중복된 전화번호로 회원가입할 수 없다', function () {
    createUser(['phone' => '01012345678']);
    
    $response = $this->post('/api/users', [
        'name' => '홍길동',
        'phone' => '01012345678',
        'email' => 'new@example.com',
        'password' => 'password123!',
        'password_confirmation' => 'password123!',
        'user_type' => 'user',
        'agreement' => '1'
    ]);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['phone']);
});

test('약관에 동의하지 않으면 회원가입할 수 없다', function () {
    $response = $this->post('/api/users', [
        'name' => '홍길동',
        'phone' => '01012345678',
        'email' => 'test@example.com',
        'password' => 'password123!',
        'password_confirmation' => 'password123!',
        'user_type' => 'user',
        'agreement' => '0'
    ]);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['agreement']);
});