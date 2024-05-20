<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Review;
use App\Models\Auction;
use Illuminate\Support\Facades\DB;
use Tests\Traits\NotSuccessfulTestTrait;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LikeTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use NotSuccessfulTestTrait;
}
