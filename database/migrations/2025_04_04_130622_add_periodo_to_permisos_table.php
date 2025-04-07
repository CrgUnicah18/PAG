<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('vacaciones', function (Blueprint $table) {
            $table->integer('periodo')->change(); // Ya existe, así que se cambia de string a integer
        });

        Schema::table('permisos', function (Blueprint $table) {
            $table->integer('periodo')->nullable(); // Se agrega la columna
        });
    }

    public function down()
    {
        Schema::table('vacaciones', function (Blueprint $table) {
            $table->string('periodo')->change(); // Volver a string si hacés rollback
        });

        Schema::table('permisos', function (Blueprint $table) {
            $table->dropColumn('periodo'); // Eliminar la columna si hacés rollback
        });
    }
};
