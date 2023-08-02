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
        Schema::create('food_rescue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rescue_id');
            $table->foreignId('food_id');
            $table->foreignId('user_id');
            $table->string('doer');
            $table->foreignId('food_rescue_status_id');
            $table->foreignId('rescue_user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_rescues');
    }
};
