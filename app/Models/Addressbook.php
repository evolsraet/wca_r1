<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addressbook extends Model
{
    use HasFactory;
    use ModelTrait;

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
