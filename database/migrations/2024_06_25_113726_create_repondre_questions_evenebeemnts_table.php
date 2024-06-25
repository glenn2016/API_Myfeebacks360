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
        Schema::create('repondre_questions_evenebeemnts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reponsefeedback_id');
            $table->foreign('reponsefeedback_id')->references('id')->on('reponsefeedbacks')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repondre_questions_evenebeemnts');
    }
};
