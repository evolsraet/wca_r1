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

    public $enums = [
        'status' => [
            'cancel' => '취소',
            'done'   => '경매완료',
            'chosen' => '선택완료',
            'wait'   => '선택대기',
            'ing'    => '경매진행',
            'diag'   => '진단대기',
            'ask'    => '신청완료',
        ],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bids()
    {
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
