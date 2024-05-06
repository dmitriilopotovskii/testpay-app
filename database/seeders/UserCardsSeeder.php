<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Seeder;

class UserCardsSeeder extends Seeder
{
    public function run(): void
    {
        Card::factory()
            ->count(3)
            ->create();
    }
}
