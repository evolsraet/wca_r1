<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Dealer;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\Traits\NotSuccessfulTestTrait;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use NotSuccessfulTestTrait;


    public function setUp(): void
    {
        parent::setUp();
        // dump('시더 실행');
        // 특정 시더 실행
        $this->artisan('db:seed');
    }

    // public function test_회원가입(): void
    // {
    //     $data = [
    //         'name' => 'John Doe',
    //         'email' => 'XsGkS@example.com',
    //         'password' => 'password',
    //         'password_confirmation' => 'password',
    //         'dummy' => 'dummy',
    //     ];

    //     $response = $this->postJson('/register', $data);
    //     // $response->dumpHeaders();
    //     // $response->dumpSession();
    //     // $response->dump();

    //     $response->assertSuccessful(); // 200번대
    //     $this->assertDatabaseHas('users', [
    //         'email' => $data['email']
    //     ]);
    // }

    // public function test_데이터()
    // {
    //     $user = User::role('user')->first();
    //     $this->actingAs($user);
    //     dd([
    //         auth()->user(),
    //         request()->all()
    //     ]);
    // }

    public function test_로그인(): void
    {
        $user = User::factory()->create([
            'status' => 'ok', // 'status' 필드를 'ok'로 임시 지정
        ]);

        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => '123123123',
        ]);
        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();
        $response->assertSuccessful();
        $this->assertAuthenticated(); // 인증됨
    }

    public function test_로그인_실패(): void
    {
        $user = User::factory()->create([
            'status' => 'ok', // 'status' 필드를 'ok'로 임시 지정
        ]);

        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => '123123123z',
        ]);

        // dd($response);

        // $response->assertJson(
        //     fn (AssertableJson $json) =>
        //     // dd($json->prop('status') == 'fail')
        //     $json->has('message')
        // );

        // $response->assertJson(
        //     fn (AssertableJson $json) =>
        //     $json->has('errors.email')
        // );

        // dd($response->getStatusCode());

        $this->assertNotSuccessful($response); // 200번대 x
        $this->assertGuest(); // 인증안됨
    }

    public function test_회원가입(): void
    {
        $user = User::factory()->make();
        $data = [
            'user' => $user->makeVisible($user->getHidden())->toArray()
        ];
        // $user->makeVisible($user->getHidden())->toArray();
        $data['user']['role'] = 'user';
        // $data['user']['password'] = '123123123';
        // dd($data);
        $response = $this->postJson('/api/users', $data);

        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();

        $response->assertSuccessful(); // 200번대
        $this->assertDatabaseHas('users', [
            'email' => $data['user']['email'],
            'status' => 'ok',
        ]);
    }

    public function test_회원가입_딜러(): void
    {
        $user = User::factory()->make();
        $dealer = Dealer::factory()->make();
        $data = [
            'user' => $user->makeVisible($user->getHidden())->toArray()
        ];
        $data['user']['role'] = 'dealer';
        $data['dealer'] = $dealer->makeVisible($dealer->getHidden())->toArray();
        unset($data['dealer']['user_id']);

        // dump($data);

        $response = $this->postJson('/api/users', $data);

        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();

        $response->assertSuccessful(); // 200번대
        $this->assertDatabaseHas('users', [
            'email' => $data['user']['email'],
            'status' => 'ask',
        ]);
    }

    public function test_유저_수정(): void
    {
        $user = User::role('user')->first();
        $this->actingAs($user);
        $response = $this->putJson("/api/users/{$user->id}", [
            'user' => [
                'name' => 'updated',
                // 'email' => 'email',
            ]
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
            'name' => 'updated',
        ]);

        // 나 아니면 수정불가
        $not_mine_id = User::role('user')->where('id', '!=', $user->id)->first();
        $response = $this->putJson("/api/users/{$not_mine_id}", [
            'user' => [
                'name' => 'updated',
                // 'email' => 'email',
            ]
        ]);
        $this->assertNotSuccessful($response); // 200번대 x
    }

    public function test_유저_수정_딜러(): void
    {
        $user = User::role('dealer')->where('status', 'ok')->first();
        $this->actingAs($user);
        $response = $this->putJson("/api/users/{$user->id}", [
            'user' => [
                'name' => 'updated',
            ],
            'dealer' => [
                'name' => 'updated',
            ]
        ]);

        // dd($user->toArray());

        $response->assertSuccessful();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'updated',
        ]);

        $this->assertDatabaseHas('dealers', [
            'user_id' => $user->id,
            'name' => 'updated',
        ]);
    }

    public function test_유저_삭제(): void
    {
        $user = User::role('user')->first();
        $this->actingAs($user);

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertSuccessful();
        // $this->assertDatabaseMissing('users', [
        $this->assertSoftDeleted('users', [
            'id' => $user->id,
        ]);

        // 나 아니면 삭제불가
        $not_mine_id = User::role('user')->where('id', '!=', $user->id)->first();
        $response = $this->deleteJson("/api/users/{$not_mine_id}", [
            'user' => [
                'name' => 'updated',
                // 'email' => 'email',
            ]
        ]);
        $this->assertNotSuccessful($response); // 200번대 x
        $this->assertDatabaseHas('users', [
            'id' => $not_mine_id->id,
        ]);
    }
}
