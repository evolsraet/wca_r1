<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarBp extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;

    protected $table        = 'car_bps';
    protected $primaryKey   = 'id';
    protected $keyType      = 'integer';
    
    protected $fillable = [
        'id',
        'name',
        'detail_id',
    ];

    protected $casts = [
        'id'         => 'integer',
        'detail_id'  => 'integer',
    ];

    // car_details 테이블과 관계
    public function carDetail()
    {
        return $this->belongsTo(CarDetail::class, 'detail_id', 'id');
    }

    // car_grades 테이블과 관계
    public function carGrades()
    {
        return $this->hasMany(CarGrade::class, 'bp_id', 'id');
    }
}
