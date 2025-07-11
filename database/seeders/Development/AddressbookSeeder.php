<?php

namespace Database\Seeders\Development;

use App\Models\Addressbook;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AddressbookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Addressbook::factory(100)->create();
    }
}
