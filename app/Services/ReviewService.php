<?php

namespace App\Services;

use App\Models\Review;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ReviewResource;

class ReviewService
{
    use CrudTrait;

    public function __construct()
    {
        // CrudTrait
        $this->defaultCrudTrait('review');
    }
}
