<?php

namespace App\Models;

use App\Models\Bid;
use App\Models\Review;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Auction extends Model implements HasMedia
{
    use HasFactory, SoftDeletes;
    use InteractsWithMedia;

    // protected $fillable = [
    //     'bank',
    //     'account'
    // ];

    public $guarded = [];

    protected $dates = [
        'final_at',
        'choice_at',
        'done_at',
        'diag_check_at',
    ];

    // 업로드 가능한 파일들
    public $files = [
        'file_auction_proxy'  => '위임장/소유자 인감증명서',
        'file_auction_owner'  => '매도자관련서류',
    ];

    // 검색어로 검색가능한 경우
    public $searchable = [
        'owner_name',
        'car_no',
        'region',
        'addr1',
        'addr2',
    ];

    public $enums = [
        'status' => [
            'cancel' => '취소',
            'done'   => '경매완료',
            'chosen' => '선택완료',
            'wait'   => '선택대기',
            'ing'    => '경매진행',
            'diag'   => '진단대기',
            'dlvr'   => '탁송중',
            'ask'    => '신청완료',
        ],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bids()
    {
        // return $this->hasMany(Bid::class);
        if (auth()->user()->hasRole('dealer')) {
            return $this->hasMany(Bid::class)->where('user_id', auth()->user()->id);
        } else {
            return $this->hasMany(Bid::class);
        }
    }

    // 리소스에서 제공
    // public function win_bid()
    // {
    //     return $this->hasOne(Bid::class, 'id', 'bid_id');
    // }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
