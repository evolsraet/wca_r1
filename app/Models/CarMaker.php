<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarMaker extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;

    protected $table        = 'car_makers';
    protected $primaryKey   = 'id';
    protected $keyType      = 'string';
    
    protected $fillable = [
        'id',
        'name',
        'name_en',
        'country',
        'import_yn',
        'logo_url',
    ];

    protected $casts = [
        'id'        => 'integer',
        'import_yn' => 'string',
    ];

    // car_models 테이블과 관계
    public function carModels()
    {
        return $this->hasMany(CarModel::class, 'maker_id', 'id');
    }
} 