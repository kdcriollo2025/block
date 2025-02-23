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
        Schema::create('allergy_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_history_id')->constrained()->onDelete('cascade');
            $table->string('allergy_name');
            $table->text('symptoms')->nullable();
            $table->text('treatment')->nullable();
            $table->date('diagnosis_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allergy_records');
    }
};
