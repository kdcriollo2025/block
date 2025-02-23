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
        Schema::table('allergy_records', function (Blueprint $table) {
            $table->foreignId('medical_history_id')
                ->constrained('medical_histories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('allergy_records', function (Blueprint $table) {
            $table->dropForeign(['medical_history_id']);
            $table->dropColumn('medical_history_id');
        });
    }
};
