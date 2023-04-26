<?php

namespace Database\Seeders;

use App\Models\GpsData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GpsDataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            $latitude = mt_rand(-90000000, 90000000) / 1000000;
            $longitude = mt_rand(-180000000, 180000000) / 1000000;

            GpsData::create([
                'latitude' => $latitude,
                'longitude' => $longitude,
                'recorded_at' => now()->subMinutes(mt_rand(1, 60 * 24 * 30)),
            ]);
        }
    }
}
