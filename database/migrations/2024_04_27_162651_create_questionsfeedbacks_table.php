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
        Schema::create('questionsfeedbacks', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->unsignedBigInteger('feddback_id')->nullable();
            $table->foreign('feddback_id')->references('id')->on('feddbacks')->onDelete('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionsfeedbacks');
    }
};
