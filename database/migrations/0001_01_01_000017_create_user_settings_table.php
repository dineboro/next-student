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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');

            // Notification preferences
            $table->boolean('notify_email')->default(true);
            $table->boolean('notify_sms')->default(true);
            $table->boolean('notify_push')->default(true);
            $table->boolean('notify_request_assigned')->default(true);
            $table->boolean('notify_request_updated')->default(true);
            $table->boolean('notify_request_completed')->default(true);
            $table->boolean('notify_new_comment')->default(true);

            // Privacy settings
            $table->boolean('profile_visible')->default(true);
            $table->boolean('show_email')->default(false);
            $table->boolean('show_phone')->default(false);
            $table->boolean('allow_messages')->default(true);

            // Theme settings
            $table->enum('theme', ['light', 'dark', 'auto'])->default('light');
            $table->string('language')->default('en');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
