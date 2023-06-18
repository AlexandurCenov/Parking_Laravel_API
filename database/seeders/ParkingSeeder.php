<?php

namespace Database\Seeders;

use App\Models\Parking;
use Illuminate\Database\Seeder;

class ParkingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Parking::create([
            'day_shift_start' => '08:00:00',
            'day_shift_end' => '17:59:00',
            'night_shift_start' => '18:00:00',
            'night_shift_end' => '07:59:00',
            'total_places' => 200,
            'left_places' => 191
        ]);
    }
}
