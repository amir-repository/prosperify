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
        Schema::create('rescue_photos', function (Blueprint $table) {
            $table->id();
            $table->string('photo');
            $table->foreignId('rescue_user_id');
            $table->foreignId('user_id');
            $table->foreignId('food_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rescue_photos');
    }
};