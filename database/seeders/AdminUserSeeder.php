<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@kirkwood.edu'],
            [
                'first_name'        => 'Admin',
                'last_name'         => 'Kirkwood',
                'email'             => 'admin@kirkwood.edu',
                'password'          => Hash::make('Admin@1234'),
                'role'              => 'admin',
                'phone_number'      => '3195550100',
                'email_verified_at' => now(),
                'approval_status'   => 'approved',
                'approved_at'       => now(),
            ]
        );

        $this->command->info('Admin user created successfully.');
        $this->command->info('   Email:    admin@kirkwood.edu');
        $this->command->info('   Password: Admin@1234');
        $this->command->warn('   Change this password after first login!');
    }
}
