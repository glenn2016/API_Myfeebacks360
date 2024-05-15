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
        Schema::create('entreprise_abonements', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->nullable();
            $table->string('email')->unique();
            $table->string('numeroTelUn')->unique();
            $table->string('numeroTelDeux')->unique()->nullable();
            $table->string('pays');
            $table->string('ville');
            $table->string('adresse');
            $table->unsignedBigInteger('usercreate')->nullable();
            $table->foreign('usercreate')->references('id')->on('users')->onDelete('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entreprise_abonements');
    }
};
