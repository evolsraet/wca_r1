<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Models\Bid;
use Illuminate\Support\Str;
use App\Http\Resources\BidResource;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\Traits\WithTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;
use App\Models\Auction;
use App\Models\Review;
use App\Models\Like;

class AuctionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    use WithTrait;

    public function toArray($request)
    {
        // 모델 인스턴스를 변수에 저장
        $auction = $this->resource;
        // print_r(['auction' => $auction->toArray()]);
        // die();

        $parentArray = parent::toArray($request);
        $addArray = [];

        // 관계 리소스로 리턴
        $this->relationResource($request, $parentArray);

        $addArray['hashid'] = Hashids::encode($auction->id);


        $addArray['bids_count'] = Bid::where('auction_id', $auction->id)->count();

        // 좋아요 카운트 
        $addArray['like_count'] = Like::where('likeable_id', $auction->id)->count();

        // Bid 의 price 의 값이 auction_id 를 기준으로 중간 3개 값을 찾아서 최소값과 최대값 그리고 평균값을 구함 
        $bids = Bid::where('auction_id', $auction->id)->select('price')->orderBy('price')->get();
        $prices = $bids->pluck('price')->sort();

        $middlePrices = collect();
        $count = $prices->count();

        if ($count >= 5) {
            // 최소값과 최대값을 제외한 중간 3개 값 선택
            $middlePrices = $prices->slice(1, $count - 2)->slice(0, 5);
            $addArray['middle_prices'] = [
                'min' => $middlePrices->min(),
                'max' => $middlePrices->max(),
                'avg' => round($middlePrices->avg(), 0),
            ];

            // 숫자의 자릿수에 따른 변환
            $min = $middlePrices->min();
            $max = $middlePrices->max();
            $avg = round($middlePrices->avg(), 0);
            
            $addArray['middle_prices_value'] = [
                'min' => strlen((string)$min) >= 5 ? floor($min / 10000) : $min,
                'max' => strlen((string)$max) >= 5 ? floor($max / 10000) : $max,
                'avg' => strlen((string)$avg) >= 5 ? floor($avg / 10000) : $avg,
            ];
        }
        
        else if ($count >= 3) {
            // 최소값과 최대값을 제외한 중간 3개 값 선택
            $middlePrices = $prices->slice(1, $count)->slice(0, 3);
            $addArray['middle_prices'] = [
                'min' => $middlePrices->min(),
                'max' => $middlePrices->max(),
                'avg' => round($middlePrices->avg(), 0),
            ];

            // 숫자의 자릿수에 따른 변환
            $min = $middlePrices->min();
            $max = $middlePrices->max();
            $avg = round($middlePrices->avg(), 0);
            
            $addArray['middle_prices_value'] = [
                'min' => strlen((string)$min) >= 5 ? floor($min / 10000) : $min,
                'max' => strlen((string)$max) >= 5 ? floor($max / 10000) : $max,
                'avg' => strlen((string)$avg) >= 5 ? floor($avg / 10000) : $avg,
            ];
        }

        else {
            // 3개 미만일 경우 최대값만 사용
            $addArray['middle_prices'] = [
                'max' => $prices->max(),
            ];

            // 숫자의 자릿수에 따른 변환
            $max = $prices->max();
            
            $addArray['middle_prices_value'] = [
                'max' => strlen((string)$max) >= 5 ? floor($max / 10000) : $max,
            ];
        }

        // 상위 5개 입찰건
        if ($parentArray['status'] != 'ask') {
            $addArray['top_bids'] = BidResource::collection(
                $this->whenLoaded(
                    'bids',
                    function () use ($auction) {
                        return $auction->bids->sortByDesc('price')->take(5);
                    },
                    function () use ($auction) {
                        return $auction->bids()->orderByDesc('price')->take(5)->get();
                    }
                )
            );

            if (in_array($parentArray['status'], ['done', 'chosen', 'dlvr'])) {
                $addArray['win_bid'] = new BidResource($auction->bids->firstWhere('id', $auction->bid_id));
            }

            if (in_array($parentArray['status'], ['chosen'])) {
                $addArray['takson_end_at'] = $auction->choice_at->addWeekdaysExcludingHolidays(config('days.taksong_day'));
            }
        }

        $addArray['claim_day'] = config('days.claim_day');

        // 날짜 필드를 Y-m-d 포맷으로 변환
        // $timestampFields = ['created_at', 'updated_at', 'deleted_at'];
        // foreach ($timestampFields as $field) {
        //     if (isset($auction->$field)) {
        //         $parentArray[$field] = $auction->$field->toDatetimeString();
        //     }
        // }


        $addArray['car_thumbnails'] = json_decode($auction->car_thumbnail, true);

        // 경매 횟수 
        $auction_count = Auction::where('user_id', $auction->user_id)->count();
        $addArray['auction_count'] = $auction_count;


        // review 아이디 확인
        $review_id = Review::where('auction_id', $auction->id)->first();
        $addArray['review_id'] = $review_id ? $review_id->id : null;


        // TODO:status_chosen 
        // 판매자 탁송정보 : user
        // 구매자 탁송정보 : dealer
        // 구매자 입금완료 : dealer_pay

        // 유저가 탁송정보를 입력하기 전까지 유저가 탁송정보를 입력하면 taksong_wish_at 에 값이 들어옴 
        // 딜러가 차량대금 입금하기 전까지 탁송신청 안됨, 입금하면 vehicle_payment_id 에 값이 들어옴 

        $addArray['status_chosen'] = $auction->status_chosen;



        $this->withFiles($parentArray, $addArray);

        // 날짜 필드를 Y-m-d 포맷으로 변환 (맨 마지막)
        foreach ($parentArray as $key => $value) {
            if (str_ends_with($key, '_at') && $value !== null && isset($this->$key) && $this->$key instanceof \Carbon\Carbon) {
                $parentArray[$key] = $this->$key->toDatetimeString();
            }
        }


        // 파일들
        return array_merge($parentArray, $addArray);
    }
}
