<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create student and instructor roles
        Role::create(['name' => 'student']);
        Role::create(['name' => 'instructor']);
        Role::create(['name' => 'admin']);
    }
}
