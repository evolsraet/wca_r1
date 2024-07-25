<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Addressbook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Traits\NotSuccessfulTestTrait;

class AddressbookTest extends TestCase
{
    use RefreshDatabase, WithFaker, NotSuccessfulTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_딜러_권한이_없는_사용자는_주소록에_접근할_수_없다(): void
    {
        $user = User::role('user')->first();

        $response = $this->actingAs($user)->getJson('/api/addressbooks');

        $this->assertNotSuccessful($response);
    }

    public function test_딜러는_자신의_주소록만_조회할_수_있다(): void
    {
        $dealer = User::role('dealer')->first();
        $otherDealer = User::role('dealer')->where('id', '!=', $dealer->id)->first();

        Addressbook::factory()->count(3)->create(['user_id' => $dealer->id]);
        Addressbook::factory()->count(2)->create(['user_id' => $otherDealer->id]);

        $response = $this->actingAs($dealer)->getJson('/api/addressbooks');

        $response->assertSuccessful();

        $responseData = $response->json('data');
        $this->assertTrue(collect($responseData)->every(fn ($item) => $item['user_id'] === $dealer->id));
    }

    public function test_관리자는_모든_주소록을_조회할_수_있다(): void
    {
        $admin = User::role('admin')->first();

        $response = $this->actingAs($admin)->getJson('/api/addressbooks?paginate=10000');
        // 모든 주소록 수 가져오기
        $totalAddressbooksCount = Addressbook::count();

        // 응답에서 받은 주소록 수
        $responseAddressbooksCount = count($response->json('data'));

        // 두 수가 일치하는지 확인
        $this->assertEquals($totalAddressbooksCount, $responseAddressbooksCount, '관리자가 조회한 주소록 수가 전체 주소록 수와 일치하지 않습니다.');

        $response->assertSuccessful();
    }

    public function test_딜러는_주소록을_생성할_수_있다(): void
    {
        $dealer = User::role('dealer')->first();

        $addressbookData = [
            'addressbook' => [
                'name' => $this->faker->name,
                'addr_post' => $this->faker->postcode,
                'addr1' => $this->faker->streetAddress,
                'addr2' => '123123',
            ]
        ];

        $response = $this->actingAs($dealer)->postJson('/api/addressbooks', $addressbookData);

        $response->assertSuccessful();

        $this->assertDatabaseHas('addressbooks', [
            'user_id' => $dealer->id,
            'name' => $addressbookData['addressbook']['name'],
        ]);
    }

    public function test_딜러는_자신의_주소록만_수정할_수_있다(): void
    {
        $dealer = User::role('dealer')->first();
        $addressbook = Addressbook::factory()->create(['user_id' => $dealer->id]);

        $updateData = [
            'addressbook' => [
                'name' => 'Updated Name'
            ]
        ];

        $response = $this->actingAs($dealer)
            ->putJson("/api/addressbooks/{$addressbook->id}", $updateData);

        $response->assertSuccessful()
            ->assertJson([
                'data' => [
                    'id' => $addressbook->id,
                    'name' => 'Updated Name',
                ]
            ]);
    }

    public function test_딜러는_다른_딜러의_주소록을_수정할_수_없다(): void
    {
        $dealer1 = User::role('dealer')->first();
        $dealer2 = User::role('dealer')->where('id', '!=', $dealer1->id)->first();
        $addressbook = Addressbook::factory()->create(['user_id' => $dealer2->id]);

        $updateData = [
            'addressbook' => [
                'name' => 'Updated Name'
            ]
        ];

        $response = $this->actingAs($dealer1)
            ->putJson("/api/addressbooks/{$addressbook->id}", $updateData);

        $this->assertNotSuccessful($response);
    }

    public function test_딜러는_자신의_주소록만_삭제할_수_있다(): void
    {
        $dealer = User::role('dealer')->first();
        $addressbook = Addressbook::factory()->create(['user_id' => $dealer->id]);

        $response = $this->actingAs($dealer)
            ->deleteJson("/api/addressbooks/{$addressbook->id}");

        $response->assertSuccessful();
        $this->assertDatabaseMissing('addressbooks', ['id' => $addressbook->id]);
    }

    public function test_딜러는_다른_딜러의_주소록을_삭제할_수_없다(): void
    {
        $dealer1 = User::role('dealer')->first();
        $dealer2 = User::role('dealer')->where('id', '!=', $dealer1->id)->first();
        $addressbook = Addressbook::factory()->create(['user_id' => $dealer2->id]);

        $response = $this->actingAs($dealer1)
            ->deleteJson("/api/addressbooks/{$addressbook->id}");

        $this->assertNotSuccessful($response);
    }

    public function test_주소록_수정시_특정_필드는_변경되지_않는다(): void
    {
        $dealer = User::role('dealer')->first();
        $addressbook = Addressbook::factory()->create(['user_id' => $dealer->id]);

        $updateData = [
            'addressbook' => [
                'name' => 'Updated Name',
                'user_id' => 1, // 이 필드는 변경되지 않아야 함
            ]
        ];

        $response = $this->actingAs($dealer)
            ->putJson("/api/addressbooks/{$addressbook->id}", $updateData);

        $response->assertSuccessful();

        // 데이터베이스에서 변경사항 확인
        $this->assertDatabaseHas('addressbooks', [
            'id' => $addressbook->id,
            'name' => 'Updated Name',
            'user_id' => $dealer->id,
        ]);

        // 응답 JSON 확인
        $response->assertJson([
            'data' => [
                'name' => 'Updated Name',
                'user_id' => $dealer->id,
            ]
        ]);
    }
}
