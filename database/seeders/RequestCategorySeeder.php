<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequestCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Engine', 'icon' => '⚙️', 'color' => '#FF5733', 'description' => 'Engine-related issues and repairs'],
            ['name' => 'Brakes', 'icon' => '🛑', 'color' => '#C70039', 'description' => 'Brake system work'],
            ['name' => 'Electrical', 'icon' => '⚡', 'color' => '#FFC300', 'description' => 'Electrical system and wiring'],
            ['name' => 'Transmission', 'icon' => '🔧', 'color' => '#900C3F', 'description' => 'Transmission repairs and service'],
            ['name' => 'Suspension', 'icon' => '🔩', 'color' => '#581845', 'description' => 'Suspension and steering'],
            ['name' => 'HVAC', 'icon' => '❄️', 'color' => '#3498DB', 'description' => 'Heating and air conditioning'],
            ['name' => 'Diagnostics', 'icon' => '🔍', 'color' => '#2ECC71', 'description' => 'Diagnostic and troubleshooting'],
            ['name' => 'Oil Change', 'icon' => '🛢️', 'color' => '#F39C12', 'description' => 'Oil change and fluid service'],
            ['name' => 'Tires', 'icon' => '⭕', 'color' => '#34495E', 'description' => 'Tire service and rotation'],
            ['name' => 'Body Work', 'icon' => '🎨', 'color' => '#E74C3C', 'description' => 'Body repairs and paint'],
            ['name' => 'Other', 'icon' => '📋', 'color' => '#95A5A6', 'description' => 'Other automotive services'],
        ];

        foreach ($categories as $category) {
            DB::table('request_categories')->insert([
                'name' => $category['name'],
                'icon' => $category['icon'],
                'color' => $category['color'],
                'description' => $category['description'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
