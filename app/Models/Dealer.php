<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Dealer extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

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
        'phone',
        'birthday'
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function setPhoneAttribute($value)
    {
        // 숫자가 아닌 모든 문자를 제거
        $this->attributes['phone'] = preg_replace('/\D+/', '', $value);
    }
}
