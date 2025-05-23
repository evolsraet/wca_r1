<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Carbon\Carbon;

class Dealer extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use ModelTrait;

    // protected $fillable = [
    //     'user_id',
    //     'name',
    //     'phone',
    //     'birthday',
    //     'company',
    //     'company_duty',
    //     'company_post',
    //     'company_addr1',
    //     'company_addr2',
    //     'recieve_post',
    //     'recieve_addr1',
    //     'recieve_addr2',
    //     'introduce',
    // ];

    protected $guarded = [];

    protected $hidden = [
        // 'phone',
        // 'birthday'
    ];

    protected $fieldName = [
        'name' => 'name',
        'phone' => 'phone',
        'birthday' => 'birthday',
        'company' => 'company',
        'company_duty' => 'company_duty',
        'company_post' => 'company_post',
        'company_addr1' => 'company_addr1',
        'company_addr2' => 'company_addr2',
        'recieve_post' => 'recieve_post',
        'recieve_addr1' => 'recieve_addr1',
        'recieve_addr2' => 'recieve_addr2',
        'introduce' => 'introduce',
    ];


    // TODO: 디비 추가 및 기본값 설정
    public $enums = [
        'status' => [
            'ok' => '입찰가능',
            'fail' => '입찰불가능',
        ],
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function setPhoneAttribute($value)
    {
        // 숫자가 아닌 모든 문자를 제거
        $this->attributes['phone'] = preg_replace('/\D+/', '', $value);
    }

    protected static function booted()
    {

        static::creating(function ($dealer) {

            // 딜러 회원가입시 대기 알림 
            
        });

        static::saving(function ($dealer) {
            // dealerBirthDate 가 870426 형식이면 1987-04-26 으로 변환
            if (!empty($dealer->dealerBirthDate)) {
                $dealer->dealerBirthDate = Carbon::parse($dealer->dealerBirthDate)->format('Y-m-d');
            }
        });
        
    }
}
