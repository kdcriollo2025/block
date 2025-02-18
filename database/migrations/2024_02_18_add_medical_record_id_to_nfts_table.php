<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('nfts', function (Blueprint $table) {
            $table->foreignId('medical_record_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('nfts', function (Blueprint $table) {
            $table->dropForeign(['medical_record_id']);
            $table->dropColumn('medical_record_id');
        });
    }
}; 