<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarDetail extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;

    protected $table        = 'car_details';
    protected $primaryKey   = 'id';
    protected $keyType      = 'integer';
    
    protected $fillable = [
        'id',
        'name',
        'model_id',
        'short_name',
        'generation_name',
        'start_date',
        'end_date',
        'image_url',
    ];

    protected $casts = [
        'id'        => 'integer',
        'model_id'  => 'integer',
    ];

    // car_models 테이블과 관계
    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'model_id', 'id');
    }

    // car_bps 테이블과 관계
    public function carBps()
    {
        return $this->hasMany(CarBp::class, 'detail_id', 'id');
    }
} 