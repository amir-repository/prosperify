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
        Schema::create('donation_food', function (Blueprint $table) {
            $table->id();
            $table->integer('outbound_plan');
            $table->integer('outbound_result')->nullable();
            $table->foreignId('donation_id');
            $table->foreignId('food_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_food');
    }
};
