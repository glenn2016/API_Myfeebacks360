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
        Schema::create('evaluation_question_reponse_evaluations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('categorie_id')->nullable();
            $table->foreign('categorie_id')->references('id')->on('categories')->onDelete('cascade')->nullable();

            $table->unsignedBigInteger('reponse_id')->nullable();
            $table->foreign('reponse_id')->references('id')->on('reponses_evaluations')->onDelete('cascade');

            $table->unsignedBigInteger('evaluatuer_id')->nullable();
            $table->foreign('evaluatuer_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('evaluer_id')->nullable();
            $table->foreign('evaluer_id')->references('id')->on('users')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_question_reponse_evaluations');
    }
};
