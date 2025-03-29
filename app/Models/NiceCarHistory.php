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
        'data'
    ];

    protected $casts = [
        'data' => 'array',
    ];

}
