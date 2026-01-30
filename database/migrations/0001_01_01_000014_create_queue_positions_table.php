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
        Schema::create('queue_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('help_request_id')->constrained()->onDelete('cascade');
            $table->integer('position')->unsigned();
            $table->integer('estimated_wait_minutes')->nullable();
            $table->timestamps();

            $table->unique('help_request_id');
            $table->index('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_positions');
    }
};
