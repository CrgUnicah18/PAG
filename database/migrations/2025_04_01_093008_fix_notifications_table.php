<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixNotificationsTable extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropPrimary(); // Eliminar la clave primaria existente
            $table->uuid('id')->primary()->change(); // Cambiar el tipo a uuid y establecer como clave primaria
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropPrimary();
            $table->string('id')->primary()->change(); // Revertir a string si es necesario
        });
    }
}
