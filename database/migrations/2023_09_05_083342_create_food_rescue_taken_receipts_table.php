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
        Schema::create('food_rescue_taken_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rescue_assignment_id')->constrained();
            $table->integer('taken_amount');
            $table->string('donor_signature');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_rescue_taken_receipts');
    }
};
