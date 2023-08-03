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
        Schema::create('food_rescue_point', function (Blueprint $table) {
            $table->id();
            $table->integer('point');
            $table->foreignId('point_id')->constrained();
            $table->foreignId('rescue_id')->constrained();
            $table->foreignId('food_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_rescue_points');
    }
};
