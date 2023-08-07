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
            $table->unsignedInteger('assigner_id')->nullable();
            $table->string('assigner_name')->nullable();
            $table->unsignedInteger('volunteer_id')->nullable();
            $table->string('volunteer_name')->nullable();
            $table->unsignedInteger('actor_id');
            $table->string('actor_name');
            $table->unsignedBigInteger('food_rescue_status_id');
            $table->string('food_rescue_status_name');
            $table->integer('amount');
            $table->dateTime('expired_date');
            $table->unsignedInteger('unit_id');
            $table->string('unit_name');
            $table->string('photo');
            $table->unsignedBigInteger('vault_id')->nullable();
            $table->string('vault_name')->nullable();
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
