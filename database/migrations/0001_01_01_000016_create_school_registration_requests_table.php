<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_registration_requests', function (Blueprint $table) {
            $table->id();
            $table->string('school_name');
            $table->string('school_domain')->unique();
            $table->text('address');
            $table->string('contact_info');
            $table->string('registration_id')->unique()->comment('State/Federal govt ID');
            $table->string('requester_email');
            $table->string('requester_name');
            $table->string('requester_phone')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_registration_requests');
    }
};
