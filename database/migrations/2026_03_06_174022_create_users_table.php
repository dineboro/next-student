<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone_number')->nullable();
            $table->enum('role', ['student', 'instructor', 'admin'])->default('student');

            // Student field
            $table->string('major')->nullable();

            // Instructor fields
            $table->enum('department', [
                'business_it',
                'liberal_arts',
                'science',
                'health_sciences',
                'trades_technology',
                'arts_humanities',
            ])->nullable();
            $table->string('badge_photo')->nullable();

            // ID numbers
            $table->string('student_id')->nullable()->unique();
            $table->string('instructor_id')->nullable()->unique();
            $table->string('profile_photo')->nullable();

            // Verification
            $table->string('verification_code')->nullable();
            $table->timestamp('verification_code_expires_at')->nullable();

            // Approval
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('approval_status');
            $table->index('verification_code');
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
