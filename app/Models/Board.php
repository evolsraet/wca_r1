<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;
    use ModelTrait;


    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
