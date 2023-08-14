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
            $table->foreignId('donation_id')->constrained();
            $table->foreignId('food_id')->constrained();
            $table->foreignId('donation_food_status_id');
            $table->integer('amount_plan')->default(0);
            $table->integer('amount_result')->default(0);
            $table->foreignId('assigner_id');
            $table->foreignId('volunteer_id');
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
