<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bays = [];
        for ($i = 1; $i <= 12; $i++) {
            $bays[] = [
                'school_id' => 1,
                'bay_number' => 'BAY-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'bay_name' => 'Work Station ' . $i,
                'status' => 'available',
                'equipment' => json_encode(['Lift', 'Tool Chest', 'Air Compressor', 'Diagnostic Computer']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('bays')->insert($bays);
    }
}
