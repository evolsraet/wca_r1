<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\TaksongStatusJob;
use App\Models\TaksongStatusTemp;
use App\Services\AuctionService;
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
        // 탁송 상태 확인
        $schedule->call(function() {
            $taksongStatusTemp = TaksongStatusTemp::where('chk_status', '!=', 'done')->get();
            if($taksongStatusTemp){
                foreach($taksongStatusTemp as $taksongStatus){
                    TaksongStatusJob::dispatch($taksongStatus->chk_id);
                }
            }

            // 차량대금 입금 확인 
            $auctionService = new AuctionService();
            $auctionService->auctionTotalDepositMiss();

            // 경매 종료 시간 만료시 선택대기로 변경     
            $auctionService->auctionFinalAtUpdate();

            
        })->everyMinute();


        $schedule->call(function() {

            

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
