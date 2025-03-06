<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medical_history_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_history_id')->constrained('medical_histories')->onDelete('cascade');
            $table->string('change_type'); // 'added', 'modified', 'deleted'
            $table->string('record_type'); // 'consultation', 'allergy', 'surgery', etc.
            $table->unsignedBigInteger('record_id');
            $table->json('changes'); // Almacena los cambios específicos
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_history_changes');
    }
}; 