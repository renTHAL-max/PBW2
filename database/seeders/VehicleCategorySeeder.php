<?php

namespace Database\Seeders;

use App\Models\VehicleCategory;
use Illuminate\Database\Seeder;

class VehicleCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'City Car', 'description' => 'Mobil kecil untuk perkotaan'],
            ['name' => 'MPV', 'description' => 'Multi Purpose Vehicle'],
            ['name' => 'SUV', 'description' => 'Sport Utility Vehicle'],
            ['name' => 'Sedan', 'description' => 'Mobil penumpang sedan'],
            ['name' => 'Motor', 'description' => 'Sepeda motor'],
        ];

        foreach ($categories as $category) {
            VehicleCategory::create($category);
        }
    }
}