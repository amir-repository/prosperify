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
        Schema::create('food_donation_given_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_assignment_id')->constrained();
            $table->integer('given_amount');
            $table->string('receipt_photo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_donation_given_receipts');
    }
};
