<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\User;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\TaksongStatusTemp;
use App\Services\MediaService;
use Illuminate\Support\Facades\Log;
use App\Jobs\AuctionDoneJob;
use App\Models\Bid;

class NameChangeService
{
    public function __construct(){}

    public function getAuction($auction_id)
    {
        // 이 부분도 대채코드 필요 
        // $taksongStatus = TaksongStatusTemp::where('auction_id', $auction_id)->get();
        $auction = Auction::find($auction_id);
        return $auction;
    }

    // 명의이전 완료된 매물 중 파일 업로드 되지 않은 매물 찾아서 상태 변경
    public function changeAuctionStatusAll()
    {
        $auction = Auction::where('status', 'dlvr')
                          ->where('is_taksong', 'done')
                          ->get();
        
        foreach($auction as $item){
            $mediaService = new MediaService();
            $media = $mediaService->getMedia($item, 'file_auction_name_change')->first();
            
            if ($media && $media->model_id === $item->id) {
                $this->changeAuctionStatus($media->model_id);
            }
        }
    }

    // 매물정보 완료로 변경 
    public function changeAuctionStatus($auction_id)
    {
        $auction = Auction::find($auction_id);

        if ($auction->status === 'done') {
            Log::info('변경 생략: 이미 done 상태임', ['auction_id' => $auction_id]);
            return;
        }
        
        $auction->status = 'done';
        $auction->save();

        Log::info('changeAuctionStatus', ['auction' => $auction_id]);

        $dealer = Bid::find($auction->bid_id);

        // 알림 전송
        AuctionDoneJob::dispatch($auction->user_id, $auction_id, 'user');
        AuctionDoneJob::dispatch($dealer->user_id, $auction_id, 'dealer');

    }

    // 명의변경 완료된 경매 중 파일이 업로드된 경매만 처리
    public function processCompletedNameChangeAuctions()
    {
        // 1. status가 dlvr 이고 is_taksong이 done인 경매 조회
        $auctions = Auction::where('status', 'dlvr')
                          ->where('is_taksong', 'done')
                          ->where('has_uploaded_name_change_file', true)
                          ->get();

        Log::info('[명의변경 완료] processCompletedNameChangeAuctions', ['auctions' => $auctions]);

        foreach ($auctions as $item) {
            // 2. media 테이블에서 model_id가 경매의 id와 일치하고, collection_name이 'file_auction_name_change'인 미디어가 있는지 확인
            $mediaService = new MediaService();

            Log::info('[명의변경 완료 확인] processCompletedNameChangeAuctions', ['item' => $item]);

            // $media = $mediaService->getMedia($item, 'file_auction_name_change')->first();

            // 3. 미디어가 있고, 해당 미디어가 현재 경매에 속한 경우에만 처리
            // if (($media && $media->model_id) === $item->id) {
                // 4. 상태를 done으로 변경
                // Log::info('NameChangeServiceID', ['auction_id' => $auction->id]);
                Auction::where('id', $item->id)->update(['status' => 'done']);

                // 5. 푸시 알림 전송
                $dealer = Bid::find($item->bid_id);
                AuctionDoneJob::dispatch($item->user_id, $item->id, 'user');
                AuctionDoneJob::dispatch($dealer->user_id, $item->id, 'dealer');

                // 이 부분도 제거 대채코드 필요 
                // TaksongStatusTemp::where('auction_id', $item->id)->update(['chk_status' => 'done']);

            // }
        }
    }

}   
