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

class AuctionDlvrJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    protected $data;
    protected $thisData;
    public function __construct($user, $data, $thisData)
    {
        $this->user = $user;
        $this->data = $data;
        $this->thisData = $thisData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $baseUrl = config('app.url');

        Log::info('탁송 신청이 완료되었습니다.', ['thisData' => $this->thisData, 'data' => $this->data]);

        $formattedDate = Carbon::parse($this->thisData['taksong_wish_at'])->format('Y-m-d(D) H시');

        $randomPrefix = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);
        $randomSuffix = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);
        $bidCode = $randomPrefix.$this->thisData['bid_id'].$randomSuffix;

        $data = [
            'title' => '모든 탁송정보가 입력되었습니다.',
            'message' => "아래 기일까지 차량대금을 입금해주세요! 탁송은 '위카탁송' 에서 진행되며 별도의 안내 문자가 발송됩니다. ",
            'data' => $this->thisData,
            'status4' => $this->thisData['final_price'],
            'status5' => $this->thisData['bank'].' '.$this->thisData['account'],
            'status6' => $formattedDate,
            'link' => $baseUrl.'/api/payment?code='.$bidCode
        ];

        $sendMessage = NotificationTemplate::basicTemplate($data);

        Log::info('탁송 신청이 완료되었습니다.', ['data' => $data]);

        // 이메일 전송
        $user = User::find($this->user);
        $user->notify(new AuctionDlvrNotification($user, $sendMessage));

        // 알리고 전송
        // $user->notify(new AligoNotification([
        //     'tpl_data' => [
        //         'tpl_code' => env('SMS_TPL_CODE'),
        //         'receiver_1' => $this->user->phone,
        //         'subject_1' => $sendMessage['title'],
        //         'message_1' => $sendMessage['message1'].'<br>'.$sendMessage['message2'].'<br>'.$sendMessage['message3'].'<br>'.$sendMessage['message7'].'<br>'.$sendMessage['message8'].'<br>'.$sendMessage['message9'],
        //     ]
        // ]));
    }
}
