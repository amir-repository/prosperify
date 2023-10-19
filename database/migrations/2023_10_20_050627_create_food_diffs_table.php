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
        Schema::create('food_diffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_id')->constrained();
            $table->integer('amount');
            $table->unsignedBigInteger('on_food_rescue_status_id');
            $table->foreignId('food_rescue_status_id')->constrained(table: "food_rescue_statuses");
            $table->unsignedBigInteger('actor_id');
            $table->string('actor_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_diffs');
    }
};
