<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NiceDNRData extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nice_dnr_datas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ownerNm',
        'vhrNo',
        'isCached',
        'data'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',  // JSON 필드를 자동으로 array로 캐스팅
        'isCached' => 'string'
    ];
}
