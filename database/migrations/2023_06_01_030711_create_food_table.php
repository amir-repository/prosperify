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
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rescue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vault_id')->nullable()->constrained();
            $table->string('name');
            $table->string('detail');
            $table->dateTime('expired_date');
            $table->integer('amount');
            $table->integer('stored_amount')->nullable();
            $table->dateTime('stored_at')->nullable();
            $table->string('photo');
            $table->foreignId('category_id')->constrained();
            $table->foreignId('sub_category_id')->constrained();
            $table->foreignId('unit_id')->constrained();
            $table->foreignId('food_rescue_status_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food');
    }
};
