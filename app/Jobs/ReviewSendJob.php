<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\AuctionsNotification;
use App\Notifications\Templates\NotificationTemplate;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\Auction;
use App\Models\Review;

class ReviewSendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $data;
    protected $type;
    /**
     * Create a new job instance.
     */
    public function __construct($userId, $data, $type='user')
    {
        $this->userId = $userId;
        $this->data = $data;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {      
        $user = User::find($this->userId);
        $data = Auction::find($this->data['auction_id']);
        $data['content'] = $this->data['content'];
    
        $notificationTemplate = NotificationTemplate::getTemplate(
            'ReviewSendJob',
            $data,
            ['mail']
        );

        try {
            $send = $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail']));
            
            if ($send) {
                Log::info('[리뷰작성 알림전송 / '.$data['review_id'].']', ['request' => $this->data]);
            }

        } catch (\Exception $e) {
            Log::error('[리뷰작성 알림전송 실패 / '.$data['review_id'].']', ['error' => $e->getMessage()]);
        }
        
    }
}
