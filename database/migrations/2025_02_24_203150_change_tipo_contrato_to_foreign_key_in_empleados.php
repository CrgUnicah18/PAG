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
            // Agregar la columna tipo_contrato_id como entero (o unsigned integer si se usa autoincremento)
            $table->unsignedBigInteger('tipo_contrato_id')->nullable();

            // Crear la relación de clave foránea
            $table->foreign('tipo_contrato_id')
                ->references('id')
                ->on('tipo_contratos')
                ->onDelete('cascade'); // Cambia esto según tu preferencia (por ejemplo, 'cascade', 'restrict', etc.)
        });
    }

    /**
     * Revocar las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empleados', function (Blueprint $table) {
            // Eliminar la clave foránea y la columna
            $table->dropForeign(['tipo_contrato_id']);
            $table->dropColumn('tipo_contrato_id');
        });
    }

};
