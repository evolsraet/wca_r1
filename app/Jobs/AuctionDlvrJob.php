<?php

namespace App\Jobs;

use App\Notifications\AligoNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Notifications\AuctionDlvrNotification;
use App\Notifications\Templates\NotificationTemplate;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Notifications\AuctionsNotification;
class AuctionDlvrJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    protected $data;
    protected $thisData;
    protected $mode;
    public function __construct($user, $data, $thisData, $mode)
    {
        $this->user = $user;
        $this->data = $data;
        $this->thisData = $thisData;
        $this->mode = $mode;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        if($this->mode == 'user'){

            $notificationTemplate = NotificationTemplate::getTemplate('AuctionDlvrJobUser', $this->thisData, ['mail']);
            $user = User::find($this->user);
            $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송

        }else if($this->mode == 'dealer'){

            $notificationTemplate = NotificationTemplate::getTemplate('AuctionDlvrJobDealer', $this->thisData, ['mail']);
            $user = User::find($this->user);
            $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송



            Log::info('탁송 신청이 완료되었습니다.', ['data' => $this->thisData]);
        
        }

    }
}
