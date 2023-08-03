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
        Schema::create('food_rescue_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rescue_id')->constrained();
            $table->foreignId('food_id')->constrained();
            $table->unsignedInteger('assigner_id');
            $table->string('assigner_name');
            $table->unsignedInteger('volunteer_id');
            $table->string('volunteer_name');
            $table->integer('amount');
            $table->integer('unit');
            $table->string('unit_name');
            $table->string('photo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_rescue_logs');
    }
};
