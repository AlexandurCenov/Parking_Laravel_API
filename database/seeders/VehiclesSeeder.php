<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehiclesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vehicle::create([
            'registration_number' => 'СО3566АB',
            'category_id' => 4,
            'card_id' => 2,
            'entered_on' => '2023-06-17 11:12:37'
        ]);
        Vehicle::create([
            'registration_number' => 'СО2566FB',
            'category_id' => 1,
            'card_id' => 3,
            'entered_on' => '2023-06-17 11:29:56'
        ]);
        Vehicle::create([
            'registration_number' => 'СО2586TB',
            'category_id' => 1,
            'card_id' => null,
            'entered_on' => '2023-06-17 11:30:25'
        ]);
        Vehicle::create([
            'registration_number' => 'С2586TB',
            'category_id' => 3,
            'card_id' => 1,
            'entered_on' => '2023-06-15 08:12:37'
        ]);
        Vehicle::create([
            'registration_number' => 'СA3386TB',
            'category_id' => 1,
            'card_id' => 1,
            'entered_on' => '2023-06-17 12:07:33'
        ]);
    }
}
