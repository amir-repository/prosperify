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
            $table->integer('phone');
            $table->string('email');
            $table->string('title');
            $table->string('description');
            $table->enum('status', ['diajukan', 'diproses', 'diambil', 'disimpan']);
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
