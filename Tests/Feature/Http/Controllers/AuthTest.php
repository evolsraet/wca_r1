<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Dealer;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
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

    public function test회원가입(): void
    {
        $user = User::factory()->make();
        $data = [
            'user' => $user->makeVisible($user->getHidden())->toArray(),
        ];
        $data['user']['role'] = 'user';
        $data['user']['password_confirmation'] = $data['user']['password'];

        $response = $this->postJson('/api/users', $data);

        // 응답 확인
        $response->assertSuccessful(); // 200번대 응답이 반환되었는지 확인

        // 데이터베이스에 사용자 정보가 올바르게 저장되었는지 검증
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
        $data['user']['password_confirmation'] = $data['user']['password'];
        $data['dealer'] = $dealer->makeVisible($dealer->getHidden())->toArray();
        unset($data['dealer']['user_id']);

        // dump($data);


        Storage::fake('media_folder'); // 파일이 실제로 저장되지 않도록 스토리지를 가짜로 설정합니다.
        $file = UploadedFile::fake()->create('file_user_sign.pdf', 100); // 100KB 크기의 PDF 파일
        $data['file_user_sign'] = $file; // 여기서는 $data 배열에 직접 추가하는 대신, 파일을 요청에 별도로 추가합니다.

        $response = $this->postJson('/api/users', $data, [
            'file_user_sign' => $file,
        ]);

        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();

        $response->assertSuccessful(); // 200번대
        $this->assertDatabaseHas('users', [
            'email' => $data['user']['email'],
            'status' => 'ask',
        ]);

        // 파일이 올바르게 저장되었는지 확인
        $fileId = $response->json()['file_result'][0]['id'];
        $fileName = $response->json()['file_result'][0]['file_name'];

        $this->assertEquals('file_user_sign.pdf', $fileName);

        // 파일이 저장될 경로
        // $filePath = "{$fileId}/{$fileName}";

        // dump($filePath);

        // 가짜 디스크에 파일이 존재하는지 확인 (경로 계속 못찾는중)
        // Storage::disk('media_folder')->assertExists($filePath); // 파일이 저장될 경로를 지정해야 합니다.
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

        Storage::fake('media_folder'); // 파일이 실제로 저장되지 않도록 스토리지를 가짜로 설정합니다.
        $file = UploadedFile::fake()->create('file_user_sign.pdf', 100); // 100KB 크기의 PDF 파일
        $data['file_user_sign'] = $file; // 여기서는 $data 배열에 직접 추가하는 대신, 파일을 요청에 별도로 추가합니다.

        // $response = $this->putJson('/api/users', $data, [
        //     'file_user_sign' => $file,
        // ]);

        $response = $this->putJson("/api/users/{$user->id}", [
            'user' => [
                'name' => 'updated',
            ],
            'dealer' => [
                'name' => 'updated',
            ],
            'file_user_sign' => $file,
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

        // 파일이 올바르게 저장되었는지 확인
        // dd($response->json());
        $fileId = $response->json()['file_result'][0]['id'];
        $fileName = $response->json()['file_result'][0]['file_name'];

        $this->assertEquals('file_user_sign.pdf', $fileName);
        // Storage::disk('media_folder')->assertExists("{$fileId}/{$fileName}");
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
        $response = $this->deleteJson("/api/users/{$not_mine_id}");
        $this->assertNotSuccessful($response); // 200번대 x
        $this->assertDatabaseHas('users', [
            'id' => $not_mine_id->id,
        ]);
    }
}
