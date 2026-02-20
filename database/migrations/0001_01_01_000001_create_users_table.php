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
            $table->unsignedBigInteger('school_id');  // Don't use foreignId yet
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone_number')->nullable();
            $table->enum('role', ['student', 'instructor', 'admin'])->default('student');
            $table->boolean('is_available')->default(false)->comment('For instructors only');
            $table->string('student_id')->nullable()->unique()->comment('Student ID number');
            $table->string('instructor_id')->nullable()->unique()->comment('Instructor ID number');
            $table->text('profile_photo')->nullable();

            // Verification fields
            $table->string('verification_code')->nullable();
            $table->timestamp('verification_code_expires_at')->nullable();

            // Approval fields
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();

            $table->text('bio')->nullable();
            $table->json('privacy_settings')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['school_id', 'role']);
            $table->index('is_available');
            $table->index('approval_status');
            $table->index('verification_code');
        });

        // Add foreign key constraint AFTER table is created
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('school_id')
                ->references('id')
                ->on('schools')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
