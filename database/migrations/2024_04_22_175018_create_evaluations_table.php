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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evaluateur_id')->nullable();
            $table->foreign('evaluateur_id')->references('id')->on('users')->onDelete('cascade')->nullable();
            $table->unsignedBigInteger('evaluer_id')->nullable();
            $table->foreign('evaluer_id')->references('id')->on('users')->onDelete('cascade')->nullable();
            $table->string('question_one');
            $table->string('question_deux');
            $table->string('question_trois');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
