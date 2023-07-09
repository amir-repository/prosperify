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
            $table->string('name');
            $table->string('detail');
            $table->dateTime('expired_date');
            $table->integer('in_stock')->nullable();
            $table->integer('amount');
            $table->string('photo');
            $table->dateTime('stored_at')->nullable();
            $table->foreignId('user_id');
            $table->foreignId('category_id');
            $table->foreignId('sub_category_id');
            $table->foreignId('unit_id');
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
