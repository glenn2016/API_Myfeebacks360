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
        Schema::table('questionsfeedbacks', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('usercreate')->nullable();
            $table->foreign('usercreate')->references('id')->on('users')->onDelete('cascade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questionsfeedbacks', function (Blueprint $table) {
            //
        });
    }
};
