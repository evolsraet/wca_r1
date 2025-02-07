<?php

namespace App\Http\Controllers\Api;

use App\Services\ReviewService;
use App\Traits\CrudControllerTrait;
use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Http\Resources\ReviewResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    use CrudControllerTrait;

    public function __construct(ReviewService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $searchText = $request->input('search_text');

        // 기본 쿼리 설정 (reviews와 auctions 테이블 조인)
        $query = Review::select('reviews.*')  // reviews 테이블의 모든 컬럼 선택
            ->join('auctions', 'reviews.auction_id', '=', 'auctions.id');

        // 검색어가 있는 경우에만 검색 조건 추가
        if ($searchText) {
            $query->where(function($q) use ($searchText) {
                $q->where('reviews.content', 'like', '%' . $searchText . '%')
                  ->orWhere('auctions.car_model', 'like', '%' . $searchText . '%')
                  ->orWhere('auctions.car_no', 'like', '%' . $searchText . '%')
                  ->orWhere('auctions.addr1', 'like', '%' . $searchText . '%');
            });
        }

        // 로그 추가: 검색어와 쿼리 확인용
        Log::info('Search Text:', ['search_text' => $searchText]);
        Log::info('Query:', ['query' => $query->toSql()]);

        // 검색 결과 페이지네이션
        $reviews = $query->with('dealer')
                         ->paginate($request->input('per_page', 10));

        // 로그 추가: 검색 결과 확인용
        Log::info('Reviews Retrieved:', ['reviews' => $reviews->toArray()]);

        return ReviewResource::collection($reviews);
    }
}
