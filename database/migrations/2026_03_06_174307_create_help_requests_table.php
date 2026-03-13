<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('help_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_instructor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('class_section_id')->nullable()->constrained('class_sections')->onDelete('set null');

            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->string('image')->nullable();

            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');

            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->text('resolution_notes')->nullable();

            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index(['assigned_instructor_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('help_requests');
    }
};
