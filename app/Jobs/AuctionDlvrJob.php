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

        $baseUrl = config('app.url');

        if($this->mode == 'user'){

            $data = [
                'title' => '판매요청이 잘 접수됐어요.',
                'message' => "매니저가 딜러의 견적과 필요서류를 확인하고 있어요. 딜러 사정에 따라 늦어지는 경우가 있습니다. 최대한 빨리 확인 후 안내드릴 예정이니, 조금만 기다려주세요!",
                'message_1' => "<이후 판매 과정>",
                'message_2' => "1. 판매서류 준비하기",
                'message_3' => "2. 탁송일정 입력",
                'message_4' => "3. (탁송일) 기사 도착&입금받기",
                'message_5' => "4. (탁송일 + 2일) 명의이전 완료",
                'message_6' => "* 더자세한 내용은 바로가기를 클릭하여 확인해 주세요.",
                'data' => $this->thisData,
                // 'status4' => $this->thisData['final_price'],
                // 'status5' => $this->thisData['bank'].' '.$this->thisData['account'],
                // 'status6' => $formattedDate,
                'linkTitle' => '바로가기',
                'link' => '/auction/'.$this->thisData['unique_number']
            ];

            $sendMessage = NotificationTemplate::basicTemplate($data);

            Log::info('탁송 신청이 완료되었습니다.(고객)', ['data' => $data]);

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



        }else if($this->mode == 'dealer'){

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
                'linkTitle' => '결제하기',
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
}
