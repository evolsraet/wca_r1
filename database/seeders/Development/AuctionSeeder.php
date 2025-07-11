<?php

namespace Database\Seeders\Development;

use App\Models\User;
use App\Models\Auction;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AuctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Auction::factory(90)->create();

        $user = User::where('email', 'user@demo.com')->first();
        if ($user) {
            Auction::factory(10)->create([
                'user_id' => $user->id
            ]);
        }
    }
}