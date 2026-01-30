<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create instructors for School 1
        $instructors = [
            [
                'school_id' => 1,
                'first_name' => 'John',
                'last_name' => 'Mitchell',
                'email' => 'j.mitchell@central-tech.edu',
                'phone_number' => '+1-555-0101',
                'role' => 'instructor',
                'is_available' => true,
                'instructor_id' => 'INST-001',
                'password' => Hash::make('password123'),
            ],
            [
                'school_id' => 1,
                'first_name' => 'Sarah',
                'last_name' => 'Rodriguez',
                'email' => 's.rodriguez@central-tech.edu',
                'phone_number' => '+1-555-0102',
                'role' => 'instructor',
                'is_available' => true,
                'instructor_id' => 'INST-002',
                'password' => Hash::make('password123'),
            ],
            [
                'school_id' => 1,
                'first_name' => 'Michael',
                'last_name' => 'Chen',
                'email' => 'm.chen@central-tech.edu',
                'phone_number' => '+1-555-0103',
                'role' => 'instructor',
                'is_available' => false,
                'instructor_id' => 'INST-003',
                'password' => Hash::make('password123'),
            ],
            [
                'school_id' => 1,
                'first_name' => 'Emily',
                'last_name' => 'Johnson',
                'email' => 'e.johnson@central-tech.edu',
                'phone_number' => '+1-555-0104',
                'role' => 'instructor',
                'is_available' => true,
                'instructor_id' => 'INST-004',
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($instructors as $instructor) {
            User::create([
                'school_id' => $instructor['school_id'],
                'first_name' => $instructor['first_name'],
                'last_name' => $instructor['last_name'],
                'email' => $instructor['email'],
                'phone_number' => $instructor['phone_number'],
                'role' => $instructor['role'],
                'is_available' => $instructor['is_available'],
                'instructor_id' => $instructor['instructor_id'],
                'password' => $instructor['password'],
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create students for School 1
        $students = [
            [
                'school_id' => 1,
                'first_name' => 'James',
                'last_name' => 'Williams',
                'email' => 'james.williams@student.central-tech.edu',
                'phone_number' => '+1-555-0201',
                'role' => 'student',
                'student_id' => 'STU-2024-001',
            ],
            [
                'school_id' => 1,
                'first_name' => 'Emma',
                'last_name' => 'Davis',
                'email' => 'emma.davis@student.central-tech.edu',
                'phone_number' => '+1-555-0202',
                'role' => 'student',
                'student_id' => 'STU-2024-002',
            ],
            [
                'school_id' => 1,
                'first_name' => 'Oliver',
                'last_name' => 'Martinez',
                'email' => 'oliver.martinez@student.central-tech.edu',
                'phone_number' => '+1-555-0203',
                'role' => 'student',
                'student_id' => 'STU-2024-003',
            ],
            [
                'school_id' => 1,
                'first_name' => 'Sophia',
                'last_name' => 'Anderson',
                'email' => 'sophia.anderson@student.central-tech.edu',
                'phone_number' => '+1-555-0204',
                'role' => 'student',
                'student_id' => 'STU-2024-004',
            ],
            [
                'school_id' => 1,
                'first_name' => 'Liam',
                'last_name' => 'Taylor',
                'email' => 'liam.taylor@student.central-tech.edu',
                'phone_number' => '+1-555-0205',
                'role' => 'student',
                'student_id' => 'STU-2024-005',
            ],
            [
                'school_id' => 1,
                'first_name' => 'Ava',
                'last_name' => 'Thomas',
                'email' => 'ava.thomas@student.central-tech.edu',
                'phone_number' => '+1-555-0206',
                'role' => 'student',
                'student_id' => 'STU-2024-006',
            ],
            [
                'school_id' => 1,
                'first_name' => 'Noah',
                'last_name' => 'Jackson',
                'email' => 'noah.jackson@student.central-tech.edu',
                'phone_number' => '+1-555-0207',
                'role' => 'student',
                'student_id' => 'STU-2024-007',
            ],
            [
                'school_id' => 1,
                'first_name' => 'Isabella',
                'last_name' => 'White',
                'email' => 'isabella.white@student.central-tech.edu',
                'phone_number' => '+1-555-0208',
                'role' => 'student',
                'student_id' => 'STU-2024-008',
            ],
            [
                'school_id' => 1,
                'first_name' => 'Ethan',
                'last_name' => 'Harris',
                'email' => 'ethan.harris@student.central-tech.edu',
                'phone_number' => '+1-555-0209',
                'role' => 'student',
                'student_id' => 'STU-2024-009',
            ],
            [
                'school_id' => 1,
                'first_name' => 'Mia',
                'last_name' => 'Martin',
                'email' => 'mia.martin@student.central-tech.edu',
                'phone_number' => '+1-555-0210',
                'role' => 'student',
                'student_id' => 'STU-2024-010',
            ],
        ];

        foreach ($students as $student) {
            User::create([
                'school_id' => $student['school_id'],
                'first_name' => $student['first_name'],
                'last_name' => $student['last_name'],
                'email' => $student['email'],
                'phone_number' => $student['phone_number'],
                'role' => $student['role'],
                'is_available' => false, // Students don't use is_available
                'student_id' => $student['student_id'],
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create users for School 2 (Automotive Excellence Academy)
        $school2Instructors = [
            [
                'school_id' => 2,
                'first_name' => 'David',
                'last_name' => 'Thompson',
                'email' => 'd.thompson@auto-excellence.edu',
                'phone_number' => '+1-555-0301',
                'role' => 'instructor',
                'is_available' => true,
                'instructor_id' => 'INST-005',
            ],
            [
                'school_id' => 2,
                'first_name' => 'Jessica',
                'last_name' => 'Garcia',
                'email' => 'j.garcia@auto-excellence.edu',
                'phone_number' => '+1-555-0302',
                'role' => 'instructor',
                'is_available' => true,
                'instructor_id' => 'INST-006',
            ],
        ];

        foreach ($school2Instructors as $instructor) {
            User::create([
                'school_id' => $instructor['school_id'],
                'first_name' => $instructor['first_name'],
                'last_name' => $instructor['last_name'],
                'email' => $instructor['email'],
                'phone_number' => $instructor['phone_number'],
                'role' => $instructor['role'],
                'is_available' => $instructor['is_available'],
                'instructor_id' => $instructor['instructor_id'],
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $school2Students = [
            [
                'school_id' => 2,
                'first_name' => 'Daniel',
                'last_name' => 'Lee',
                'email' => 'daniel.lee@student.auto-excellence.edu',
                'phone_number' => '+1-555-0401',
                'role' => 'student',
                'student_id' => 'STU-2024-101',
            ],
            [
                'school_id' => 2,
                'first_name' => 'Charlotte',
                'last_name' => 'Moore',
                'email' => 'charlotte.moore@student.auto-excellence.edu',
                'phone_number' => '+1-555-0402',
                'role' => 'student',
                'student_id' => 'STU-2024-102',
            ],
            [
                'school_id' => 2,
                'first_name' => 'Matthew',
                'last_name' => 'Clark',
                'email' => 'matthew.clark@student.auto-excellence.edu',
                'phone_number' => '+1-555-0403',
                'role' => 'student',
                'student_id' => 'STU-2024-103',
            ],
        ];

        foreach ($school2Students as $student) {
            User::create([
                'school_id' => $student['school_id'],
                'first_name' => $student['first_name'],
                'last_name' => $student['last_name'],
                'email' => $student['email'],
                'phone_number' => $student['phone_number'],
                'role' => $student['role'],
                'is_available' => false,
                'student_id' => $student['student_id'],
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create notification preferences for all users
        $allUsers = User::all();
        foreach ($allUsers as $user) {
            DB::table('notification_preferences')->insert([
                'user_id' => $user->id,
                'sms_enabled' => true,
                'email_enabled' => true,
                'in_app_enabled' => true,
                'notify_on_request_assigned' => true,
                'notify_on_request_completed' => true,
                'notify_on_new_comment' => true,
                'notify_on_status_change' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Created ' . count($instructors) + count($school2Instructors) . ' instructors');
        $this->command->info('Created ' . count($students) + count($school2Students) . ' students');
        $this->command->info('Default password for all users: password123');
    }
}
