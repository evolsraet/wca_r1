<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Notifications\AuctionDiagNotification;
use App\Notifications\AligoNotification;
use App\Notifications\Templates\NotificationTemplate;
use Illuminate\Support\Facades\Log;
use App\Notifications\AuctionsNotification;
class AuctionDiagJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    protected $data;    
    public function __construct($user, $data)
    {
        $this->user = $user;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $user = User::find($this->user);
        $admin = User::where('id', config('services.diagnostic_admin.admin_id'))->first();


        // 유저 알림전송
        $notificationTemplate = NotificationTemplate::getTemplate('AuctionDiagJob', $this->data, ['mail']);
        $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송


        // 진단팀 알림전송
        $notificationTemplate = NotificationTemplate::getTemplate('AuctionDiagJobAdmin', $this->data, ['mail']);
        $admin->notify(new AuctionsNotification($admin, $notificationTemplate, ['mail'])); // 메일 전송

        // 알리고 알림톡 전송 
        // $user->notify(new AligoNotification([
        //     'tpl_data' => [
        //         'tpl_code' => env('SMS_TPL_CODE'),
        //         'receiver_1' => $user->phone,
        //         'subject_1' => $sendMessage['title'],
        //         'message_1' => $sendMessage['message1'].'<br>'.$sendMessage['message2'].'<br>'.$sendMessage['message3'].'<br>'.$sendMessage['message4'].'<br>'.$sendMessage['footerMsg'],
        //     ],
        // ]));
    }
}
