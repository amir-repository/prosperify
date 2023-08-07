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
            $table->foreignId('rescue_id')->constrained();
            $table->foreignId('food_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('food_rescue_status_id')->constrained(
                table: 'food_rescue_statuses',
                indexName: 'id'
            );
            $table->foreignId('assigner_id')->nullable();
            $table->foreignId('volunteer_id')->nullable();
            $table->foreignId('vault_id')->nullable()->constrained();
            $table->integer('amount_plan');
            $table->integer('amount_result')->nullable();
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
