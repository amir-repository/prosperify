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
        Schema::create('rescues', function (Blueprint $table) {
            $table->id();
            $table->string('donor_name');
            $table->string('pickup_address');
            $table->string('phone');
            $table->string('email');
            $table->string('title');
            $table->string('description');
            $table->timestamp('rescue_date');
            $table->integer('score')->nullable();
            $table->enum('status', ['direncanakan', 'diajukan', 'diproses', 'diambil', 'disimpan', 'selesai']);
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rescues');
    }
};
