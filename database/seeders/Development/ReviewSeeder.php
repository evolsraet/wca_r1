<?php

namespace Database\Seeders\Development;

use App\Models\Article;
use App\Models\Auction;
use App\Models\User;
use App\Models\Bid;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 완료된 경매 중에서 아직 리뷰가 없는 경매들 찾기
        $completedAuctions = Auction::whereIn('status', ['done', 'finished'])
            ->whereDoesntHave('articles', function($query) {
                $query->where('board_id', 'review');
            })
            ->with(['bids' => function($query) {
                $query->orderBy('price', 'desc')->limit(1);
            }])
            ->get();

        $reviewCount = 0;
        $maxReviews = 15; // 최대 생성할 리뷰 수

        foreach ($completedAuctions as $auction) {
            if ($reviewCount >= $maxReviews) {
                break;
            }

            // 70% 확률로 리뷰 생성 (모든 경매에 리뷰가 있으면 부자연스러움)
            if (fake()->boolean(70)) {
                // 해당 경매의 낙찰자(최고가 입찰자) 찾기
                $winningBid = $auction->bids->first();
                $dealerId = $winningBid ? $winningBid->user_id : null;

                // 경매 출품자(일반 사용자)가 리뷰 작성
                $seller = $auction->user;
                
                if ($seller && $seller->hasRole('user') && $dealerId) {
                    $rating = fake()->numberBetween(3, 5); // 3-5점 (긍정적 리뷰 위주)
                    $ratingLabels = ['별로예요', '괜찮아요', '좋아요', '만족해요', '최고예요!'];
                    
                    // 별점에 따른 리뷰 제목과 내용 생성
                    $titles = [
                        3 => ['거래 완료했습니다', '나쁘지 않았어요', '괜찮은 거래였습니다'],
                        4 => ['좋은 거래였습니다', '만족스러운 거래', '신뢰할 수 있는 딜러님', '추천합니다'],
                        5 => ['최고의 거래!', '완벽한 딜러님!', '강력 추천!', '정말 감사합니다', '다시 거래하고 싶어요']
                    ];
                    
                    $contents = [
                        3 => [
                            '거래는 완료되었지만 약간 아쉬운 부분이 있었습니다.',
                            '전체적으로 무난한 거래였습니다.',
                            '큰 문제없이 거래가 진행되었습니다.'
                        ],
                        4 => [
                            '친절하고 신속한 처리로 만족스러운 거래였습니다.',
                            '약속된 시간에 정확히 진행되어 좋았습니다.',
                            '전문적이고 믿을 수 있는 딜러님이었습니다.',
                            '다음에도 기회가 되면 거래하고 싶습니다.'
                        ],
                        5 => [
                            '정말 친절하고 전문적인 딜러님이었습니다. 모든 과정이 완벽했어요!',
                            '빠르고 정확한 처리, 합리적인 가격까지! 정말 만족합니다.',
                            '이런 딜러님을 만나서 정말 운이 좋았습니다. 적극 추천드려요!',
                            '처음부터 끝까지 정말 만족스러운 거래였습니다. 감사합니다!',
                            '다른 분들에게도 자신있게 추천할 수 있는 딜러님입니다.'
                        ]
                    ];

                    Article::create([
                        'board_id' => 'review',
                        'title' => fake()->randomElement($titles[$rating]),
                        'content' => fake()->randomElement($contents[$rating]),
                        'user_id' => $seller->id,
                        'extra1' => (string) $auction->id, // 경매 ID
                        'extra2' => json_encode([
                            'dealer_id' => $dealerId,
                            'rating' => $rating
                        ], JSON_UNESCAPED_UNICODE),
                        'created_at' => fake()->dateTimeBetween($auction->updated_at, 'now'),
                        'updated_at' => fake()->dateTimeBetween($auction->updated_at, 'now')
                    ]);

                    $reviewCount++;
                    
                    $this->command->info("리뷰 생성: 경매 #{$auction->id} (별점: {$rating}점 - {$ratingLabels[$rating-1]})");
                }
            }
        }

        $this->command->info("총 {$reviewCount}개의 리뷰가 생성되었습니다.");
    }
}