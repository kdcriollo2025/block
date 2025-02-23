<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cedula', 20)->nullable();
            $table->string('type')->default('user');
            $table->boolean('first_login')->default(true);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('cedula');
            $table->dropColumn('type');
            $table->dropColumn('first_login');
        });
    }
}; 