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
        Schema::create('email_verification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('verification_code');
            $table->string('ip_address', 45)->nullable();
            $table->enum('action', ['sent', 'verified', 'failed', 'expired']);
            $table->timestamp('action_at');
            $table->timestamps();

            $table->index('user_id');
            $table->index('action_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_verification_logs');
    }
};
