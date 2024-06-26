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
        Schema::table('repondre_questions_evenebeemnts', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('reponsefeedback_id')->nullable();
            $table->foreign('reponsefeedback_id')->references('id')->on('reponsefeedbacks')->onDelete('cascade')->nullable();
  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repondre_questions_evenebeemnts', function (Blueprint $table) {
            //
        });
    }
};
