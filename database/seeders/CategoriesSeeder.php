<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => 'Car',
            'category' => 'A',
            'number_of_places' => 1,
            'day_tariff' => 3,
            'night_tariff' => 2,
        ]);
        Category::create([
            'name' => 'Motor',
            'category' => 'A',
            'number_of_places' => 1,
            'day_tariff' => 3,
            'night_tariff' => 2,
        ]);
        Category::create([
            'name' => 'Van',
            'category' => 'B',
            'number_of_places' => 2,
            'day_tariff' => 6,
            'night_tariff' => 4,
        ]);
        Category::create([
            'name' => 'Bus',
            'category' => 'C',
            'number_of_places' => 4,
            'day_tariff' => 12,
            'night_tariff' => 8,
        ]);
        Category::create([
            'name' => 'Truck',
            'category' => 'C',
            'number_of_places' => 4,
            'day_tariff' => 12,
            'night_tariff' => 8,
        ]);
    }
}
