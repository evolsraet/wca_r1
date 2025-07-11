<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(
    Tests\TestCase::class,
    // RefreshDatabase 제거 - TestCase에서 DatabaseTransactions 사용
)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * 테스트용 일반 사용자 생성
 */
function createUser(array $attributes = []): \App\Models\User
{
    $user = \App\Models\User::factory()->create(array_merge([
        'status' => 'ok',
    ], $attributes));
    
    $user->assignRole('user');
    
    return $user;
}

/**
 * 테스트용 딜러 생성
 */
function createDealer(array $attributes = []): \App\Models\User
{
    $user = \App\Models\User::factory()->create(array_merge([
        'status' => 'ok',
        'company_name' => '테스트 자동차',
        'business_number' => '123-45-67890',
    ], $attributes));
    
    $user->assignRole('dealer');
    
    return $user;
}

/**
 * 테스트용 관리자 생성
 */
function createAdmin(array $attributes = []): \App\Models\User
{
    $user = \App\Models\User::factory()->create(array_merge([
        'status' => 'ok',
    ], $attributes));
    
    $user->assignRole('admin');
    
    return $user;
}

/**
 * 사용자로 로그인
 */
function actingAsUser(array $attributes = []): \App\Models\User
{
    $user = createUser($attributes);
    test()->actingAs($user);
    return $user;
}

/**
 * 딜러로 로그인
 */
function actingAsDealer(array $attributes = []): \App\Models\User
{
    $dealer = createDealer($attributes);
    test()->actingAs($dealer);
    return $dealer;
}

/**
 * 관리자로 로그인
 */
function actingAsAdmin(array $attributes = []): \App\Models\User
{
    $admin = createAdmin($attributes);
    test()->actingAs($admin);
    return $admin;
}