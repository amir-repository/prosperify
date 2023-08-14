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
        Schema::create('donation_food_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->constrained();
            $table->foreignId('food_id')->constrained();
            $table->unsignedBigInteger('assigner_id')->nullable();
            $table->string('assigner_name')->nullable();
            $table->unsignedBigInteger('volunteer_id')->nullable();
            $table->string('volunteer_name')->nullable();
            $table->unsignedBigInteger('actor_id');
            $table->string('actor_name');
            $table->unsignedBigInteger('food_donation_status_id');
            $table->string('food_donation_status_name');
            $table->integer('amount');
            $table->dateTime('expired_date');
            $table->unsignedBigInteger('unit_id');
            $table->string('unit_name');
            $table->string('photo');
            $table->unsignedBigInteger('vault_id');
            $table->string('vault_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_food_logs');
    }
};
