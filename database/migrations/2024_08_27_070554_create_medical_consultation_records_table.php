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
            $table->unsignedBigInteger('medical_history_id');
            $table->date('consultation_date');
            $table->string('reason', 255)->nullable(); // <-- Agregamos reason
            $table->string('symptoms', 255);
            $table->string('diagnosis', 255);
            $table->string('treatment', 255);
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
