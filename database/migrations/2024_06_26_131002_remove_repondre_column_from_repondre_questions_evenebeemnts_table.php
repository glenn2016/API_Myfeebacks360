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
            $table->dropForeign(['reponsefeedback_id']);
            $table->dropColumn('reponsefeedback_id');
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
