<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $done_count = \App\Models\Auction::where('status', 'done')->doesntHave('reviews')->count() - 5;
        // $done_count = 10;
        // dump(\App\Models\Auction::where('status', 'done')->doesntHave('reviews')->count());
        if ($done_count < 5) {
            $done_count = 0;
        }
        Review::factory($done_count)->create();
    }
}