<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Article;
use App\Models\User;
use App\Models\Auction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ArticleRatingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles if they don't exist
        Role::firstOrCreate(['name' => 'user']);
        Role::firstOrCreate(['name' => 'dealer']);
        Role::firstOrCreate(['name' => 'admin']);
        
        // Create test boards if they don't exist
        DB::table('boards')->insertOrIgnore([
            'id' => 'review',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        DB::table('boards')->insertOrIgnore([
            'id' => 'free',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Temporarily disable mass assignment protection for Article model
        Article::unguard();
    }
    
    protected function tearDown(): void
    {
        // Re-enable mass assignment protection
        Article::reguard();
        parent::tearDown();
    }

    /** @test */
    public function it_can_calculate_dealer_rating_for_single_review()
    {
        // Given: 딜러와 사용자 생성
        $dealer = User::factory()->create();
        $dealer->assignRole('dealer');
        
        $user = User::factory()->create();
        $user->assignRole('user');
        
        $auction = Auction::factory()->create(['user_id' => $user->id]);
        
        // 딜러 테이블에 딜러 추가
        DB::table('dealers')->insert([
            'user_id' => $dealer->id,
            'name' => 'Test Dealer',
            'phone' => '010-1234-5678',
            'birthday' => '1990-01-01',
            'biz_check' => 'confirmed',
            'company' => 'Test Company',
            'company_duty' => 'Manager',
            'company_post' => '12345',
            'company_addr1' => 'Test Address',
            'company_addr2' => 'Test Detail',
            'receive_post' => '12345',
            'receive_addr1' => 'Receive Address',
            'receive_addr2' => 'Receive Detail',
            'introduce' => 'Test Introduction',
            'rate' => 0.0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // When: 리뷰 생성 (평점 4)
        $review = Article::create([
            'board_id' => 'review',
            'title' => '좋은 거래였습니다',
            'content' => '만족스러운 거래',
            'user_id' => $user->id,
            'extra1' => (string) $auction->id,
            'extra2' => (string) $dealer->id,
            'extra3' => '4'
        ]);

        // Then: 딜러 평점이 4.0으로 업데이트됨
        $dealerRecord = DB::table('dealers')->where('user_id', $dealer->id)->first();
        $this->assertEquals(4.0, $dealerRecord->rate);
    }

    /** @test */
    public function it_can_calculate_average_rating_for_multiple_reviews()
    {
        // Given: 딜러와 사용자들 생성
        $dealer = User::factory()->create();
        $dealer->assignRole('dealer');
        
        $users = User::factory()->count(3)->create();
        $users->each(fn($user) => $user->assignRole('user'));
        
        $auctions = $users->map(fn($user) => Auction::factory()->create(['user_id' => $user->id]));
        
        // 딜러 테이블에 딜러 추가
        DB::table('dealers')->insert([
            'user_id' => $dealer->id,
            'name' => 'Test Dealer',
            'phone' => '010-1234-5678',
            'birthday' => '1990-01-01',
            'biz_check' => 'confirmed',
            'company' => 'Test Company',
            'company_duty' => 'Manager',
            'company_post' => '12345',
            'company_addr1' => 'Test Address',
            'company_addr2' => 'Test Detail',
            'receive_post' => '12345',
            'receive_addr1' => 'Receive Address',
            'receive_addr2' => 'Receive Detail',
            'introduce' => 'Test Introduction',
            'rate' => 0.0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // When: 여러 리뷰 생성 (평점 3, 4, 5 → 평균 4.0)
        $ratings = [3, 4, 5];
        foreach ($users as $index => $user) {
            Article::create([
                'board_id' => 'review',
                'title' => "리뷰 제목 {$index}",
                'content' => "리뷰 내용 {$index}",
                'user_id' => $user->id,
                'extra1' => (string) $auctions[$index]->id,
                'extra2' => (string) $dealer->id,
                'extra3' => (string) $ratings[$index]
            ]);
        }

        // Then: 딜러 평점이 4.0으로 업데이트됨 (3+4+5)/3 = 4.0
        $dealerRecord = DB::table('dealers')->where('user_id', $dealer->id)->first();
        $this->assertEquals(4.0, $dealerRecord->rate);
    }

    /** @test */
    public function it_updates_rating_when_review_is_updated()
    {
        // Given: 딜러와 초기 리뷰
        $dealer = User::factory()->create();
        $dealer->assignRole('dealer');
        
        $user = User::factory()->create();
        $user->assignRole('user');
        
        $auction = Auction::factory()->create(['user_id' => $user->id]);
        
        DB::table('dealers')->insert([
            'user_id' => $dealer->id,
            'name' => 'Test Dealer',
            'phone' => '010-1234-5678',
            'birthday' => '1990-01-01',
            'biz_check' => 'confirmed',
            'company' => 'Test Company',
            'company_duty' => 'Manager',
            'company_post' => '12345',
            'company_addr1' => 'Test Address',
            'company_addr2' => 'Test Detail',
            'receive_post' => '12345',
            'receive_addr1' => 'Receive Address',
            'receive_addr2' => 'Receive Detail',
            'introduce' => 'Test Introduction',
            'rate' => 0.0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $review = Article::create([
            'board_id' => 'review',
            'title' => '초기 리뷰',
            'content' => '초기 내용',
            'user_id' => $user->id,
            'extra1' => (string) $auction->id,
            'extra2' => (string) $dealer->id,
            'extra3' => '3'
        ]);

        // 초기 평점 확인
        $dealerRecord = DB::table('dealers')->where('user_id', $dealer->id)->first();
        $this->assertEquals(3.0, $dealerRecord->rate);

        // When: 리뷰 평점 수정 (3 → 5)
        $review->update(['extra3' => '5']);

        // Then: 딜러 평점이 5.0으로 업데이트됨
        $dealerRecord = DB::table('dealers')->where('user_id', $dealer->id)->first();
        $this->assertEquals(5.0, $dealerRecord->rate);
    }

    /** @test */
    public function it_updates_rating_when_review_is_deleted()
    {
        // Given: 딜러와 2개의 리뷰
        $dealer = User::factory()->create();
        $dealer->assignRole('dealer');
        
        $users = User::factory()->count(2)->create();
        $users->each(fn($user) => $user->assignRole('user'));
        
        $auctions = $users->map(fn($user) => Auction::factory()->create(['user_id' => $user->id]));
        
        DB::table('dealers')->insert([
            'user_id' => $dealer->id,
            'name' => 'Test Dealer',
            'phone' => '010-1234-5678',
            'birthday' => '1990-01-01',
            'biz_check' => 'confirmed',
            'company' => 'Test Company',
            'company_duty' => 'Manager',
            'company_post' => '12345',
            'company_addr1' => 'Test Address',
            'company_addr2' => 'Test Detail',
            'receive_post' => '12345',
            'receive_addr1' => 'Receive Address',
            'receive_addr2' => 'Receive Detail',
            'introduce' => 'Test Introduction',
            'rate' => 0.0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $review1 = Article::create([
            'board_id' => 'review',
            'title' => '리뷰 1',
            'content' => '내용 1',
            'user_id' => $users[0]->id,
            'extra1' => (string) $auctions[0]->id,
            'extra2' => (string) $dealer->id,
            'extra3' => '3'
        ]);

        $review2 = Article::create([
            'board_id' => 'review',
            'title' => '리뷰 2',
            'content' => '내용 2',
            'user_id' => $users[1]->id,
            'extra1' => (string) $auctions[1]->id,
            'extra2' => (string) $dealer->id,
            'extra3' => '5'
        ]);

        // 초기 평점 확인 (3+5)/2 = 4.0
        $dealerRecord = DB::table('dealers')->where('user_id', $dealer->id)->first();
        $this->assertEquals(4.0, $dealerRecord->rate);

        // When: 한 리뷰 삭제
        $review1->delete();

        // Then: 딜러 평점이 5.0으로 업데이트됨 (남은 리뷰가 5점이므로)
        $dealerRecord = DB::table('dealers')->where('user_id', $dealer->id)->first();
        $this->assertEquals(5.0, $dealerRecord->rate);
    }

    /** @test */
    public function it_can_recalculate_all_dealer_ratings()
    {
        // Given: 여러 딜러와 리뷰들
        $dealer1 = User::factory()->create();
        $dealer1->assignRole('dealer');
        
        $dealer2 = User::factory()->create();
        $dealer2->assignRole('dealer');
        
        $users = User::factory()->count(4)->create();
        $users->each(fn($user) => $user->assignRole('user'));
        
        $auctions = $users->map(fn($user) => Auction::factory()->create(['user_id' => $user->id]));
        
        // 딜러들 생성
        foreach ([$dealer1, $dealer2] as $dealer) {
            DB::table('dealers')->insert([
                'user_id' => $dealer->id,
                'name' => 'Test Dealer',
                'phone' => '010-1234-5678',
                'birthday' => '1990-01-01',
                'biz_check' => 'confirmed',
                'company' => 'Test Company',
                'company_duty' => 'Manager',
                'company_post' => '12345',
                'company_addr1' => 'Test Address',
                'company_addr2' => 'Test Detail',
                'receive_post' => '12345',
                'receive_addr1' => 'Receive Address',
                'receive_addr2' => 'Receive Detail',
                'introduce' => 'Test Introduction',
                'rate' => 0.0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 리뷰들 생성 (이벤트 발생 막기 위해 직접 DB 삽입)
        DB::table('articles')->insert([
            [
                'board_id' => 'review',
                'title' => '리뷰 1',
                'content' => '내용 1',
                'user_id' => $users[0]->id,
                'extra1' => (string) $auctions[0]->id,
                'extra2' => (string) $dealer1->id,
                'extra3' => '4',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'board_id' => 'review',
                'title' => '리뷰 2',
                'content' => '내용 2',
                'user_id' => $users[1]->id,
                'extra1' => (string) $auctions[1]->id,
                'extra2' => (string) $dealer1->id,
                'extra3' => '2',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'board_id' => 'review',
                'title' => '리뷰 3',
                'content' => '내용 3',
                'user_id' => $users[2]->id,
                'extra1' => (string) $auctions[2]->id,
                'extra2' => (string) $dealer2->id,
                'extra3' => '5',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'board_id' => 'review',
                'title' => '리뷰 4',
                'content' => '내용 4',
                'user_id' => $users[3]->id,
                'extra1' => (string) $auctions[3]->id,
                'extra2' => (string) $dealer2->id,
                'extra3' => '3',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // When: 모든 딜러 평점 재계산
        $updatedCount = Article::recalculateAllDealerRatings();

        // Then: 2명의 딜러 평점이 업데이트됨
        $this->assertEquals(2, $updatedCount);
        
        // dealer1 평점 확인 (4+2)/2 = 3.0
        $dealer1Record = DB::table('dealers')->where('user_id', $dealer1->id)->first();
        $this->assertEquals(3.0, $dealer1Record->rate);
        
        // dealer2 평점 확인 (5+3)/2 = 4.0
        $dealer2Record = DB::table('dealers')->where('user_id', $dealer2->id)->first();
        $this->assertEquals(4.0, $dealer2Record->rate);
    }

    /** @test */
    public function it_only_updates_rating_for_review_articles()
    {
        // Given: 딜러와 일반 게시글
        $dealer = User::factory()->create();
        $dealer->assignRole('dealer');
        
        $user = User::factory()->create();
        $user->assignRole('user');
        
        DB::table('dealers')->insert([
            'user_id' => $dealer->id,
            'name' => 'Test Dealer',
            'phone' => '010-1234-5678',
            'birthday' => '1990-01-01',
            'biz_check' => 'confirmed',
            'company' => 'Test Company',
            'company_duty' => 'Manager',
            'company_post' => '12345',
            'company_addr1' => 'Test Address',
            'company_addr2' => 'Test Detail',
            'receive_post' => '12345',
            'receive_addr1' => 'Receive Address',
            'receive_addr2' => 'Receive Detail',
            'introduce' => 'Test Introduction',
            'rate' => 0.0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // free 게시판은 setUp()에서 이미 생성됨

        // When: 일반 게시글 생성 (board_id가 'review'가 아님)
        $article = Article::create([
            'board_id' => 'free',
            'title' => '일반 게시글',
            'content' => '일반 내용',
            'user_id' => $user->id,
            'extra2' => (string) $dealer->id,
            'extra3' => '5'
        ]);

        // Then: 딜러 평점이 업데이트되지 않음
        $dealerRecord = DB::table('dealers')->where('user_id', $dealer->id)->first();
        $this->assertEquals(0.0, $dealerRecord->rate);
    }

    /** @test */
    public function it_handles_missing_dealer_gracefully()
    {
        // Given: 존재하지 않는 딜러 ID
        $user = User::factory()->create();
        $user->assignRole('user');
        
        $auction = Auction::factory()->create(['user_id' => $user->id]);
        $nonExistentDealerId = 99999;

        // When: 존재하지 않는 딜러에 대한 리뷰 생성
        $review = Article::create([
            'board_id' => 'review',
            'title' => '리뷰',
            'content' => '내용',
            'user_id' => $user->id,
            'extra1' => (string) $auction->id,
            'extra2' => (string) $nonExistentDealerId,
            'extra3' => '4'
        ]);

        // Then: 에러 없이 처리됨 (dealers 테이블에 해당 딜러가 없으므로 업데이트되지 않음)
        $this->assertDatabaseHas('articles', [
            'id' => $review->id,
            'extra2' => (string) $nonExistentDealerId,
            'extra3' => '4'
        ]);
    }
}
