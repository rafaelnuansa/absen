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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('name');
            $table->string('company');
            $table->string('manager');
            $table->string('director');
            $table->string('phone');
            $table->string('address');
            $table->string('description');
            $table->string('logo');
            $table->string('email');
            $table->string('email_domain');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
