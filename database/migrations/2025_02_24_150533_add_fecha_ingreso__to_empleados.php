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
        Schema::table('empleados', function (Blueprint $table) {
            $table->date('fecha_ingreso')->nullable(); // Haciendo que el campo sea opcional por si algunos empleados no lo tienen.
            $table->integer('vacaciones_tomadas')->default(0);
            $table->string('tipo_contrato')->nullable();
        });
    }

    public function down()
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropColumn('fecha_ingreso');
        });
    }

};
