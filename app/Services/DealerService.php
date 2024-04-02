<?php

namespace App\Services;

use App\Models\User;
use App\Models\Dealer;
use App\Traits\CrudTrait;

class DealerService
{
    use CrudTrait;

    public function __construct()
    {
        // CrudTrait
        $this->defaultCrudTrait('dealer');
    }
}
