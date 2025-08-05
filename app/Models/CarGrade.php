<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarGrade extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;

    protected $table        = 'car_grades';
    protected $primaryKey   = 'id';
    protected $keyType      = 'integer';
    
    protected $fillable = [
        'id',
        'name',
        'bp_id',
        'type_name',
        'car_type_id',
        'car_type_name',
        'shape_category_id',
        'shape_category_name',
        'purpose_id',
        'purpose_name',
        'displacement',
        'gearbox',
        'gearbox_auto',
        'gearbox_manual',
        'fuel',
        'seating_capacity',
        'grade_newcar_price',
        'start_date',
        'end_date',
        'created_at',
    ];

    protected $casts = [
        'id'                 => 'integer',
        'bp_id'              => 'integer',
        'car_type_id'        => 'integer',
        'shape_category_id'  => 'integer',
        'purpose_id'         => 'integer',
        'seating_capacity'   => 'integer',
        'grade_newcar_price' => 'integer',
        'created_at'          => 'datetime',
    ];

    // car_bps 테이블과 관계
    public function carBp()
    {
        return $this->belongsTo(CarBp::class, 'bp_id', 'id');
    }
}
