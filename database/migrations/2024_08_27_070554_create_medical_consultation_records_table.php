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
        Schema::create('medical_consultation_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_history_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('medicos')->onDelete('cascade');
            $table->dateTime('consultation_date');
            $table->text('reason');
            $table->text('symptoms');
            $table->text('diagnosis');
            $table->text('treatment');
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_consultation_records');
    }
};
