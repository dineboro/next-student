<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = [
            ['school_id' => 1, 'vehicle_vin' => '1HGBH41JXMN109186', 'make' => 'Toyota', 'model' => 'Camry', 'year' => 2018, 'color' => 'Silver'],
            ['school_id' => 1, 'vehicle_vin' => '2T1BURHE3FC123456', 'make' => 'Honda', 'model' => 'Civic', 'year' => 2019, 'color' => 'Blue'],
            ['school_id' => 1, 'vehicle_vin' => '3FA6P0HD5FR234567', 'make' => 'Ford', 'model' => 'F-150', 'year' => 2020, 'color' => 'Red'],
            ['school_id' => 1, 'vehicle_vin' => '5YFBURHE6HP345678', 'make' => 'Chevrolet', 'model' => 'Silverado', 'year' => 2017, 'color' => 'Black'],
            ['school_id' => 1, 'vehicle_vin' => '1C4RJFAG1FC456789', 'make' => 'Jeep', 'model' => 'Cherokee', 'year' => 2019, 'color' => 'White'],
        ];

        foreach ($vehicles as $vehicle) {
            DB::table('vehicles')->insert([
                'school_id' => $vehicle['school_id'],
                'vehicle_vin' => $vehicle['vehicle_vin'],
                'make' => $vehicle['make'],
                'model' => $vehicle['model'],
                'year' => $vehicle['year'],
                'color' => $vehicle['color'],
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
