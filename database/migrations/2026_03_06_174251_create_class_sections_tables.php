<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->string('course_name');
            $table->string('course_code');
            $table->enum('semester', ['Fall', 'Spring', 'Summer']);
            $table->year('year');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['instructor_id', 'is_active']);
        });

        Schema::create('class_section_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_section_id')
                ->constrained('class_sections')
                ->onDelete('cascade');
            $table->foreignId('student_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->timestamps();

            $table->unique(['class_section_id', 'student_id']);
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_section_students');
        Schema::dropIfExists('class_sections');
    }
};
