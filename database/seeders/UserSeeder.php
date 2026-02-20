<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create instructors for School 1 (Kirkwood)
        $instructors = [
            [
                'school_id' => 1,
                'first_name' => 'John',
                'last_name' => 'Mitchell',
                'email' => 'j.mitchell@kirkwood.edu',  //  Changed from central-tech.edu
                'phone_number' => '+1-555-0101',
                'role' => 'instructor',
                'is_available' => true,
                'instructor_id' => 'INST-001',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'approval_status' => 'approved',  //  Pre-approved for testing
                'approved_at' => now(),
                'verification_code' => null,  //  No pending verification
                'verification_code_expires_at' => null,
            ],
            [
                'school_id' => 1,
                'first_name' => 'Sarah',
                'last_name' => 'Rodriguez',
                'email' => 's.rodriguez@kirkwood.edu',  //  Changed
                'phone_number' => '+1-555-0102',
                'role' => 'instructor',
                'is_available' => true,
                'instructor_id' => 'INST-002',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'approval_status' => 'approved',
                'approved_at' => now(),
                'verification_code' => null,  //  No pending verification
                'verification_code_expires_at' => null,
            ],
            [
                'school_id' => 1,
                'first_name' => 'Michael',
                'last_name' => 'Chen',
                'email' => 'm.chen@kirkwood.edu',  //  Changed
                'phone_number' => '+1-555-0103',
                'role' => 'instructor',
                'is_available' => false,
                'instructor_id' => 'INST-003',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'approval_status' => 'approved',
                'approved_at' => now(),
                'verification_code' => null,  //  No pending verification
                'verification_code_expires_at' => null,
            ],
        ];

        foreach ($instructors as $instructor) {
            $user = User::create($instructor);
            $user->assignRole('instructor');

            // Create default settings
            $user->settings()->create([]);
        }

        // Create students for School 1 (Kirkwood)
        $students = [
            [
                'school_id' => 1,
                'first_name' => 'James',
                'last_name' => 'Williams',
                'email' => 'james.williams@kirkwood.edu',  //  Changed
                'phone_number' => '+1-555-0201',
                'role' => 'student',
                'student_id' => 'STU-2024-001',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'approval_status' => 'approved',
                'approved_at' => now(),
                'verification_code' => null,  //  No pending verification
                'verification_code_expires_at' => null,
            ],
            [
                'school_id' => 1,
                'first_name' => 'Emma',
                'last_name' => 'Davis',
                'email' => 'emma.davis@kirkwood.edu',  //  Changed
                'phone_number' => '+1-555-0202',
                'role' => 'student',
                'student_id' => 'STU-2024-002',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'approval_status' => 'approved',
                'approved_at' => now(),
            ],
        ];

        foreach ($students as $student) {
            $user = User::create($student);
            $user->assignRole('student');

            // Create default settings
            $user->settings()->create([]);
        }

        // Create instructors for School 2 (Auto Excellence)
        $school2Instructor = [
            'school_id' => 2,
            'first_name' => 'David',
            'last_name' => 'Thompson',
            'email' => 'd.thompson@auto-excellence.edu',
            'phone_number' => '+1-555-0301',
            'role' => 'instructor',
            'is_available' => true,
            'instructor_id' => 'INST-004',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'approval_status' => 'approved',
            'approved_at' => now(),
        ];

        $user = User::create($school2Instructor);
        $user->assignRole('instructor');
        $user->settings()->create([]);

        $this->command->info('Created test users with password: password123');
    }
}
