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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('school_name');
            $table->string('school_domain')->unique();
            $table->string('address')->nullable();
            $table->string('contact_info')->nullable();
            $table->string('registration_id')->unique()
                ->comment('State/Federal government registration ID');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                ->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
//            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();

            $table->index('approval_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
