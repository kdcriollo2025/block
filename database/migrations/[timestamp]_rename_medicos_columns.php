<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('medicos', function (Blueprint $table) {
            $table->renameColumn('specialty', 'especialidad');
            $table->renameColumn('phone_number', 'telefono');
        });
    }

    public function down()
    {
        Schema::table('medicos', function (Blueprint $table) {
            $table->renameColumn('especialidad', 'specialty');
            $table->renameColumn('telefono', 'phone_number');
        });
    }
}; 