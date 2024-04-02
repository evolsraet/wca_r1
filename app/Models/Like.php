<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    public function likeable()
    {
        return $this->morphTo();
    }

    // 'model'과 'rel_id'를 사용하여 동적으로 연관된 모델을 찾는 메서드를 구현할 수 있습니다.
    // 이는 특정한 구현에 따라 달라질 수 있으므로, 여기서는 구체적인 예시를 제공하지 않습니다.
}
