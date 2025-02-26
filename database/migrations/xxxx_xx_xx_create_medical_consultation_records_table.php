<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medical_consultation_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_history_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()->constrained('medicos')->onDelete('set null');
            $table->date('consultation_date');
            $table->string('reason')->nullable();
            $table->string('symptoms');
            $table->string('diagnosis');
            $table->string('treatment');
            $table->date('next_appointment')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_consultation_records');
    }
}; 