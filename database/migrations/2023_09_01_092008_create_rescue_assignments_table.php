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
        Schema::create('rescue_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_id')->constrained();
            $table->foreignId('rescue_id')->constrained();
            $table->foreignId('volunteer_id')->nullable()->constrained(table: 'users', indexName: 'id');
            $table->foreignId('vault_id')->constrained();
            $table->unsignedBigInteger('assigner_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rescue_assignments');
    }
};
