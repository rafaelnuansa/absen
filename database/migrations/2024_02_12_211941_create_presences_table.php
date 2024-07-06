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
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->date('date');
            $table->time('time_in');
            $table->time('time_out')->nullable();
            $table->string('picture_in')->nullable();
            $table->string('picture_out')->nullable();
            $table->string('latitude_longitude_in')->nullable();
            $table->string('latitude_longitude_out')->nullable();
            $table->enum('status', ['present', 'on_leave', 'sick', 'absent'])->default('present');
            $table->text('information')->nullable();
            $table->tinyInteger('shift')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
