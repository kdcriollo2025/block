<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->text('diagnosis');
            $table->text('treatment')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Para mantener el registro incluso si se "elimina"

            // Añadimos las foreign keys con onDelete cascade
            $table->foreign('patient_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('doctor_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_records');
    }
}; 