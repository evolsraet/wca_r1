<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id',
        'user_id',
        'ip',
        'status',
        'changes'
    ];

}
