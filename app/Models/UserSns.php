<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSns extends Model
{
    use HasFactory;
    protected $table = 'user_sns';
    
    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'provider_email',
        'provider_data',
    ];

    protected $casts = [
        'provider_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
