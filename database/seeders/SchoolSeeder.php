<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('schools')->insert([
            [
                'school_name' => 'Kirkwood Community College',
                'school_domain' => 'kirkwood.edu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'school_name' => 'Automotive Excellence Academy',
                'school_domain' => 'auto-excellence.edu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
