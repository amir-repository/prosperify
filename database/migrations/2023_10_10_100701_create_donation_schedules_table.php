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
        Schema::create('donation_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_food_id')->constrained(table: 'donation_food')->cascadeOnDelete();
            $table->foreignId('donation_id')->constrained();
            $table->foreignId('food_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_schedules');
    }
};
