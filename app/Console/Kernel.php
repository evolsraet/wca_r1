<?php

namespace App\Console;

use App\Models\User;
use App\Models\Auction;
use App\Jobs\AuctionDoneJob;
use App\Jobs\TaksongStatusJob;
use App\Services\MediaService;
use App\Services\AuctionService;
use App\Models\TaksongStatusTemp;
use App\Services\NameChangeService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Console\Scheduling\Schedule;
use App\Jobs\AuctionBidStatusJob;
use App\Http\Controllers\Api\AuctionController;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    
    protected function schedule(Schedule $schedule)
    {
        $admin = User::where('id', 1)->first();
        Auth::login($admin); // 명시적으로 시스템 유저를 로그인                

        Log::info('Kernel schedule start : user : ' . auth()->user()->name);

        $schedule->call(function() {

            // 경매 상태 변경 확인
            $auctionService = new AuctionService();
            $auctionService->diagnosticCheck();
            
        })->everyMinute();


        $schedule->call(function() {

            // 탁송 상태 확인
            $taksongStatusTemp = TaksongStatusTemp::where('chk_status', '!=', 'done')->get();
            if($taksongStatusTemp){
                foreach($taksongStatusTemp as $taksongStatus){
                    TaksongStatusJob::dispatch($taksongStatus->chk_id);
                }
            }

        })->everyMinute();

         // 경매 종료 시간 만료시 선택대기로 변경     
        $schedule->call(function() {
            $auctionService = new AuctionService();
            $auctionService->auctionFinalAtUpdate();
        })->everyMinute();


        $schedule->call(function() {
            // 명의이전 까지 완료된 매물 상태변경
            $nameChangeService = new NameChangeService();
            // $nameChangeService->changeAuctionStatusAll();
            $nameChangeService->processCompletedNameChangeAuctions();
        })->everyMinute();


        $schedule->call(function() {

            // 차량대금 입금 확인 
            $auctionService = new AuctionService();
            $auctionService->auctionTotalDepositMiss();
            // 경매 종료 시간 만료시 선택대기 2일동안 아무 내용 없으면 자동으로 취소 처리 
            $auctionService->auctionCancel();

        })->hourly();

        // 경매 완료 수수료 처리 확인 / 하루 한번
        $schedule->call(function() {
            $auctionService = new AuctionService();
            $auctionService->auctionAfterFeeDone();
        })->dailyAt('00:00');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
