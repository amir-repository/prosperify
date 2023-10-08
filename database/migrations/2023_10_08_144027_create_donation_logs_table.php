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
        Schema::create('donation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('donation_status_id');
            $table->string('donation_status_name');
            $table->unsignedBigInteger('actor_id');
            $table->string('actor_name');
            $table->string('title');
            $table->string('description');
            $table->dateTime('donation_date');
            $table->unsignedBigInteger('user_Id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_logs');
    }
};
