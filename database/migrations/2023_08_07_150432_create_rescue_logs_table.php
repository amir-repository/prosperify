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
        Schema::create('rescue_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rescue_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('rescue_status_id');
            $table->string('rescue_status_name');
            $table->unsignedBigInteger('actor_id');
            $table->string('actor_name');
            $table->integer('food_rescue_plan')->nullable();
            $table->integer('food_rescue_result')->nullable();
            $table->string('donor_name');
            $table->string('pickup_address');
            $table->string('phone');
            $table->string('email');
            $table->string('title');
            $table->string('description');
            $table->dateTime('rescue_date');
            $table->integer('score')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rescue_logs');
    }
};
