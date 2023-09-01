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
        Schema::create('vault_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_rescue_log_id')->constrained();
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
        Schema::dropIfExists('vault_logs');
    }
};
