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
            $table->string('telephoneFixe')->nullable();
            $table->string('adressEntreprise')->nullable();
            $table->string('ville')->nullable();
            $table->string('pays')->nullable();
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
