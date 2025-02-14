<?php

namespace App\Models;

use App\Models\Bid;
use App\Models\Like;
use App\Models\User;
use App\Models\Review;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Auction extends Model implements HasMedia
{
    use HasFactory, SoftDeletes;
    use InteractsWithMedia;
    use ModelTrait;


    // protected $fillable = [
    //     'bank',
    //     'account'
    // ];

    public $guarded = [];

    // protected $dates = [
    //     'final_at',
    //     'choice_at',
    //     'done_at',
    //     'diag_check_at',
    // ];

    protected $casts = [
        'final_at' => 'datetime',
        'choice_at' => 'datetime',
        'taksong_wish_at' => 'datetime',
        'done_at' => 'datetime',
        'diag_check_at' => 'datetime',
        'diag_first_at' => 'datetime',
        'diag_second_at' => 'datetime',
        'car_maker' => 'string',
        'car_model' => 'string',
        'car_model_sub' => 'string',
        'car_grade' => 'string',
        'car_grade_sub' => 'string',
        'car_year' => 'string',
        'car_first_reg_date' => 'datetime',
        'car_mission' => 'string',
        'car_fuel' => 'string',
        'car_price_now' => 'string',
        'car_price_now_whole' => 'string',
        'car_km' => 'string'
    ];

    // 업로드 가능한 파일들
    public $files = [
        'file_auction_proxy'  => '위임장/소유자 인감증명서',
        'file_auction_owner'  => '매도자관련서류',
        'file_auction_car_license'  => '자동차등록증',
    ];
    public $files_one = [];

    // 검색어로 검색가능한 경우
    public $searchable = [
        'owner_name',
        'car_no',
        'region',
        'addr1',
        'addr2',
        'car_model'
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


    // 파일
    public function registerMediaCollections(): void
    {
        foreach ($this->files as $file => $name) {
            $mediaCollection = $this->addMediaCollection($file)
                ->useFallbackUrl('/images/placeholder.jpg')
                ->useFallbackPath(public_path('/images/placeholder.jpg'));

            // $files_one 배열에 있는 항목에만 singleFile() 적용
            if (array_key_exists($file, $this->files_one)) {
                $mediaCollection->singleFile();
            }
        }
    }

    public function registerMediaConversions(Media $media = null): void
    {
        if (env('RESIZE_IMAGE') === true) {
            $this->addMediaConversion('resized-image')
                ->width(env('IMAGE_WIDTH', 300))
                ->height(env('IMAGE_HEIGHT', 300));
        }
    }
}
