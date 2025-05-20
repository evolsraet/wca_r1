<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagInfo extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'diag_data'];

    protected $casts = [
        'diag_data' => 'array', // JSON으로 자동 변환
    ];
}
