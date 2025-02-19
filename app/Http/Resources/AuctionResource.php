<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Models\Bid;
use Illuminate\Support\Str;
use App\Http\Resources\BidResource;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\Traits\WithTrait;
use Illuminate\Http\Resources\Json\JsonResource;

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

        $addArray['bids_count'] = Bid::where('auction_id', $auction->id)->count();

        // Bid 의 price 의 값이 auction_id 를 기준으로 중간 3개 값을 찾아서 최소값과 최대값 그리고 평균값을 구함 
        $bids = Bid::where('auction_id', $auction->id)->get();
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
        }
        
        else if ($count >= 3) {
            // 최소값과 최대값을 제외한 중간 3개 값 선택
            $middlePrices = $prices->slice(1, $count - 2)->slice(0, 3);
            $addArray['middle_prices'] = [
                'min' => $middlePrices->min(),
                'max' => $middlePrices->max(),
                'avg' => round($middlePrices->avg(), 0),
            ];
        }

        else {
            // 3개 미만일 경우 최대값만 사용
            $addArray['middle_prices'] = [
                'max' => $prices->max(),
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
                $addArray['takson_end_at'] = $auction->choice_at->addWeekdaysExcludingHolidays(env('TAKSONG_DAY', 0));
            }
        }

        // 날짜 필드를 Y-m-d 포맷으로 변환
        // $timestampFields = ['created_at', 'updated_at', 'deleted_at'];
        // foreach ($timestampFields as $field) {
        //     if (isset($auction->$field)) {
        //         $parentArray[$field] = $auction->$field->toDatetimeString();
        //     }
        // }

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
