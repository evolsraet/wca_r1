<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Article;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 기존 리뷰 데이터 구조 변경: extra2에서 dealer_id와 rating 분리
        $reviews = Article::where('board_id', 'review')->whereNotNull('extra2')->get();
        
        foreach ($reviews as $review) {
            $extra2Data = json_decode($review->extra2, true);
            
            if ($extra2Data && isset($extra2Data['dealer_id']) && isset($extra2Data['rating'])) {
                // extra2에는 dealer_id만 저장
                $review->extra2 = (string) $extra2Data['dealer_id'];
                
                // extra3에는 rating만 저장
                $review->extra3 = (string) $extra2Data['rating'];
                
                $review->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 원래 구조로 복원: dealer_id와 rating을 JSON으로 결합
        $reviews = Article::where('board_id', 'review')->whereNotNull('extra2')->get();
        
        foreach ($reviews as $review) {
            if ($review->extra2 && $review->extra3) {
                $review->extra2 = json_encode([
                    'dealer_id' => (int) $review->extra2,
                    'rating' => (int) $review->extra3
                ], JSON_UNESCAPED_UNICODE);
                
                $review->extra3 = null;
                $review->save();
            }
        }
    }
};
