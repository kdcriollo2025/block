<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nfts', function (Blueprint $table) {
            $table->id();
            $table->string('asset_id');
            $table->string('name');
            $table->string('owner');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nfts');
    }
}; 