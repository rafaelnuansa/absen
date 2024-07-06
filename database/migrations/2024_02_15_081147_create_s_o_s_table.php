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
        Schema::create('s_o_s', function (Blueprint $table) {
            $table->id();
            // Di buat oleh employee
            $table->foreignId('employee_id')->references('id')->on('employees')->onDelete('cascade');
            // Titik darurat
            $table->foreignId('checkpoint_id')->references('id')->on('checkpoints')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_o_s');
    }
};
