<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('help_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('bay_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('request_categories')->onDelete('set null');
            $table->foreignId('assigned_instructor_id')->nullable()->constrained('users')->onDelete('set null');

            $table->string('title')->nullable();
            $table->text('description');
            $table->enum('priority_level', ['low', 'medium', 'high', 'emergency'])->default('medium');
            $table->enum('status', ['pending', 'assigned', 'in_progress', 'completed', 'cancelled'])->default('pending');

            // Time tracking
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            // Additional info
            $table->text('cancellation_reason')->nullable();
            $table->text('resolution_notes')->nullable()->comment('Instructor notes on how issue was resolved');
            $table->integer('estimated_duration_minutes')->nullable();

            $table->timestamps();

            // Indexes for performance
            $table->index(['student_id', 'status']);
            $table->index(['assigned_instructor_id', 'status']);
            $table->index(['status', 'priority_level']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_requests');
    }
};
