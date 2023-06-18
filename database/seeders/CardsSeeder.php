<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Seeder;

class CardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Card::create([
            'name' => 'Silver',
            'discount' => 0.1
        ]);
        Card::create([
            'name' => 'Gold',
            'discount' => 0.15
        ]);
        Card::create([
            'name' => 'Platinum',
            'discount' => 0.2
        ]);
    }
}
