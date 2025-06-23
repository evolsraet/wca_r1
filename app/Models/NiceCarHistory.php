<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NiceCarHistory extends Model
{
    use HasFactory;

    protected $table = 'nice_carhistorys';

    protected $fillable = [
        'car_no',
        'first_regdate',
        'data',
        'ip',
        'device',
        'user_id',
        'user_agent',
        'response_status'
    ];

    protected $casts = [
        'data' => 'array',
    ];

}
