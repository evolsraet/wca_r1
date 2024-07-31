<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = ['year', 'month', 'day', 'name'];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'day' => 'integer',
    ];

    /**
     * 년월일을 'yyyy-mm-dd' 형식의 날짜로 반환하는 가상 속성입니다.
     *
     * @var string
     */
    protected $appends = ['date'];

    /**
     * 'date' 가상 속성의 접근자입니다.
     *
     * @return string
     */
    public function getDateAttribute()
    {
        return sprintf('%04d-%02d-%02d', $this->year, $this->month, $this->day);
    }
}
