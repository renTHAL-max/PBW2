<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $cityCarId = VehicleCategory::where('name', 'City Car')->first()->id;
        $mpvId = VehicleCategory::where('name', 'MPV')->first()->id;
        $suvId = VehicleCategory::where('name', 'SUV')->first()->id;

        $vehicles = [
            [
                'vehicle_category_id' => $cityCarId,
                'license_plate' => 'B 1234 ABC',
                'brand' => 'Toyota',
                'model' => 'Agya',
                'year' => 2022,
                'color' => 'Putih',
                'price_per_day' => 250000,
                'status' => 'tersedia',
                'seat_capacity' => 5,
                'transmission' => 'manual',
                'fuel_type' => 'bensin',
                'description' => 'Mobil city car irit dan nyaman untuk dalam kota',
            ],
            [
                'vehicle_category_id' => $mpvId,
                'license_plate' => 'B 5678 XYZ',
                'brand' => 'Toyota',
                'model' => 'Avanza',
                'year' => 2023,
                'color' => 'Silver',
                'price_per_day' => 350000,
                'status' => 'tersedia',
                'seat_capacity' => 7,
                'transmission' => 'automatic',
                'fuel_type' => 'bensin',
                'description' => 'MPV keluarga 7 penumpang dengan transmisi automatic',
            ],
            [
                'vehicle_category_id' => $mpvId,
                'license_plate' => 'B 9012 DEF',
                'brand' => 'Daihatsu',
                'model' => 'Xenia',
                'year' => 2021,
                'color' => 'Hitam',
                'price_per_day' => 300000,
                'status' => 'tersedia',
                'seat_capacity' => 7,
                'transmission' => 'manual',
                'fuel_type' => 'bensin',
                'description' => 'MPV ekonomis untuk keluarga',
            ],
            [
                'vehicle_category_id' => $suvId,
                'license_plate' => 'B 3456 GHI',
                'brand' => 'Toyota',
                'model' => 'Fortuner',
                'year' => 2023,
                'color' => 'Putih',
                'price_per_day' => 600000,
                'status' => 'tersedia',
                'seat_capacity' => 7,
                'transmission' => 'automatic',
                'fuel_type' => 'diesel',
                'description' => 'SUV mewah untuk perjalanan jarak jauh',
            ],
            [
                'vehicle_category_id' => $mpvId,
                'license_plate' => 'B 7890 JKL',
                'brand' => 'Honda',
                'model' => 'Mobilio',
                'year' => 2022,
                'color' => 'Merah',
                'price_per_day' => 320000,
                'status' => 'tersedia',
                'seat_capacity' => 7,
                'transmission' => 'manual',
                'fuel_type' => 'bensin',
                'description' => 'MPV dengan kabin luas dan nyaman',
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::create($vehicle);
        }
    }
}