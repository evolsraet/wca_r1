<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;

    protected $table        = 'car_models';
    protected $primaryKey   = 'id';
    protected $keyType      = 'integer';
    
    protected $fillable = [
        'id',
        'name',
        'maker_id',
    ];

    protected $casts = [
        'id'        => 'integer',
        'maker_id'  => 'integer',
    ];

    // car_makers 테이블과 관계
    public function carMaker()
    {
        return $this->belongsTo(CarMaker::class, 'maker_id', 'id');
    }

    // car_details 테이블과 관계
    public function carDetails()
    {
        return $this->hasMany(CarDetail::class, 'model_id', 'id');
    }
}
