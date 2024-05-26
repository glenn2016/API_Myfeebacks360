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
        Schema::table('contact_abonements', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('Abonnement_id')->nullable();
            $table->foreign('Abonnement_id')->references('id')->on('abonnements')->onDelete('cascade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_abonements', function (Blueprint $table) {
            //
        });
    }
};
