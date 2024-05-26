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
        Schema::create('abonnement_utlisateurs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('abonnement_id')->nullable();
            $table->foreign('abonnement_id')->references('id')->on('abonnements')->onDelete('cascade')->nullable();
            $table->unsignedBigInteger('utlisateur_id')->nullable();
            $table->foreign('utlisateur_id')->references('id')->on('users')->onDelete('cascade')->nullable();
            $table->date('date_debut_abonement');
            $table->date('date_fin_abonement');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abonnement_utlisateurs');
    }
};
