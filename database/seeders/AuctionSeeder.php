<?php

namespace Database\Seeders;

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
        $user = User::where('email', 'user@demo.com')->first();

        Auction::factory(100)->create();
        if ($user) {
            Auction::factory(30)->create([
                'user_id' => $user->id
            ]);
        }
    }
}
