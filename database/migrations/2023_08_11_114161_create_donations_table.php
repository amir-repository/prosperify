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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->integer('score');
            $table->integer('food_donation_plan');
            $table->integer('food_donation_result');
            $table->dateTime('donation_date');
            $table->foreignId('recipient_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('donation_status_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
