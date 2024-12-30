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

class AuctionDlvrJob implements ShouldQueue
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
        // 이메일 전송
        $user = User::find($this->user);
        $user->notify(new AuctionDlvrNotification($user, $this->data));

        // 알리고 전송
        // $aligo = new AligoNotification();
        // $aligo->sendSms($this->data['mobile'], '탁송중입니다.');
    }
}
