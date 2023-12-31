<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CardsSeeder::class,
            CategoriesSeeder::class,
            ParkingSeeder::class,
            VehiclesSeeder::class
        ]);
    }
}
