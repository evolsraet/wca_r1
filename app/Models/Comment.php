<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    use ModelTrait;

    // 수정불가 항목 : ModelTrait 에 정의
    protected $immutable = [
        'commentable_type',
        'commentable_id',
        'user_id',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function article()
    {
        return $this->with(['commentable' => function ($query) {
            $query->where('commentable_type', Article::class);
        }])->get();
    }

    public function user()
    {
        return $this->belongsTo(User::class)
            ->with('media')
            ->withoutGlobalScopes();
    }
}
