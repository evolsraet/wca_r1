<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Auction;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ReviewResource;
use App\Jobs\ReviewSendJob;
class ReviewService
{
    use CrudTrait;

    public function __construct()
    {
        // CrudTrait
        $this->defaultCrudTrait('review');
    }

    protected function middleProcess($method, $request, $result, $id = null)
    {
        switch ($method) {
            case 'store':
                $auction = Auction::find($request['auction_id']);
                $result->user_id = auth()->user()->id;

                if (!$auction or $auction->status != 'done')
                    throw new \Exception('리뷰작성 가능한 경매건이 아닙니다.');

                $this->modifyAuth($auction->user_id);
                
                // $this->sendReviewNotification($result);

                break;
            case 'update':
            case 'destroy':
                $this->modifyAuth($result->user_id);
                break;
            default:
                break;
        }

        if ($method == 'store') {
        } elseif ($method == 'update' or $method == 'delete') {
        }
        // 컨트롤러에서 이 메소드를 오버라이드하여 사용할 수 있습니다.
    }

    public function modifyAuth($user_id)
    {
        if (!auth()->user()->hasPermissionTo('act.admin') && $user_id != auth()->user()->id) {
            throw new \Exception('권한이 없습니다.');
        }
    }

    public function sendReviewNotification($result)
    {
        $user = $result->user;
        $data = [
            'review' => $result,
        ];

        $via = ['mail'];

        ReviewSendJob::dispatch($user, $data, $via);
    }
}
