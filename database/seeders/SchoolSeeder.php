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
                'address' => '6301 Kirkwood Blvd SW, Cedar Rapids, IA 52404',
                'contact_info' => '+1-319-398-5411',
                'registration_id' => 'IOWA-EDU-001234',
                'approval_status' => 'approved',
                'approved_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'school_name' => 'Automotive Excellence Academy',
                'school_domain' => 'auto-excellence.edu',
                'address' => '123 Tech Drive, Iowa City, IA 52240',
                'contact_info' => '+1-319-555-0100',
                'registration_id' => 'IOWA-EDU-005678',
                'approval_status' => 'approved',
                'approved_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
