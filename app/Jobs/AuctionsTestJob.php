<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Notifications\Templates\NotificationTemplate;
use App\Notifications\AuctionsNotification;
use App\Models\Auction;
class AuctionsTestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    protected $data;
    protected $via;

    public function __construct($user, $data, $via=['mail'])
    {
        $this->user = $user;
        $this->data = $data;
        $this->via = $via;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //

        $user = User::find(3);
        $data = [
            'user' => $user,
            'status' => 'no',
            'name' => '홍길동',
            'title' => '경매 이름 변경 알림',
            'message' => '경매 이름이 변경되었습니다.',
        ];

        // $data = 412351;

        $auction = Auction::find(104);

        // dd($auction);
        
        // 회원가입시 템플릿
        $notificationTemplate = NotificationTemplate::getTemplate('welcome', $data, ['mail']);
        // $auctionsNotification = new AuctionsNotification($user, $notificationTemplate, ['mail']);
        $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['aligo'])); // 알리고 알림톡 전송


        // $notificationTemplate = NotificationTemplate::getTemplate('userStatus', $data, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송


        // $data = 412351;
        // $notificationTemplate = NotificationTemplate::getTemplate('UserResetPasswordNotification', $data, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송


        // $notificationTemplate = NotificationTemplate::getTemplate('AuctionBidStatusJobAsk', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송


        // $notificationTemplate = NotificationTemplate::getTemplate('AuctionBidStatusJobWait', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송


        // $notificationTemplate = NotificationTemplate::getTemplate('AuctionBidStatusJobCancel', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송


        // $notificationTemplate = NotificationTemplate::getTemplate('AuctionBidStatusJobReauction', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송

        // $notificationTemplate = NotificationTemplate::getTemplate('AuctionCancelJob', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송

        // $notificationTemplate = NotificationTemplate::getTemplate('AuctionCohosenJobDealer', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송

        // $notificationTemplate = NotificationTemplate::getTemplate('AuctionCohosenJobUser1', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송

        // $notificationTemplate = NotificationTemplate::getTemplate('AuctionCohosenJobUser2', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송

        // $notificationTemplate = NotificationTemplate::getTemplate('AuctionDlvrJobUser', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송

        // $notificationTemplate = NotificationTemplate::getTemplate('AuctionDlvrJobDealer', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송

        // $notificationTemplate = NotificationTemplate::getTemplate('AuctionDoneJobUser', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송

        // $notificationTemplate6 = NotificationTemplate::getTemplate('AuctionDoneJobDealer', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate6, ['mail']));


        // $notificationTemplate7 = NotificationTemplate::getTemplate('AuctionIngJob', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate7, ['mail']));


        // $notificationTemplate8 = NotificationTemplate::getTemplate('AuctionStartJob', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate8, ['mail']));


        // $notificationTemplate9 = NotificationTemplate::getTemplate('AuctionTotalAfterFeeJob', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate9, ['mail']));


        // $notificationTemplate10 = NotificationTemplate::getTemplate('AuctionTotalDepositJobUser', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate10, ['mail']));


        // $notificationTemplate11 = NotificationTemplate::getTemplate('AuctionTotalDepositJobDealer', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate11, ['mail']));


        // $notificationTemplate12 = NotificationTemplate::getTemplate('AuctionTotalDepositMissJob', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate12, ['mail']));


        // $notificationTemplate13 = NotificationTemplate::getTemplate('TaksongNameChangeFileUploadJob', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate13, ['mail']));


        // $notificationTemplate14 = NotificationTemplate::getTemplate('TaksongNameChangeJobUser', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate14, ['mail']));


        // $notificationTemplate15 = NotificationTemplate::getTemplate('TaksongNameChangeJobDealer', $auction, ['mail']);
        // $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate15, ['mail']));
        




        // return $notificationTemplate;
    }
}
