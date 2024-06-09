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
        Schema::create('questions_evaluations_categorie', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('questions_evaluations_id')->nullable();
            $table->foreign('questions_evaluations_id')->references('id')->on('questions_evaluations')->onDelete('cascade')->nullable();
            $table->unsignedBigInteger('categorie_id')->nullable();
            $table->foreign('categorie_id')->references('id')->on('categories')->onDelete('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions_evaluations_categorie');
    }
};
