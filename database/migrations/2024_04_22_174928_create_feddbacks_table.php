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
        Schema::create('feddbacks', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->unsignedBigInteger('evenement_id')->nullable();
            $table->foreign('evenement_id')->references('id')->on('evenements')->onDelete('cascade')->nullable();            $table->integer('etat')->default(1)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feddbacks');
    }
};
