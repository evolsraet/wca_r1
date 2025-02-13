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
