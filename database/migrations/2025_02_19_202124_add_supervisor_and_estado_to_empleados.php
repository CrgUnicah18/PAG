<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->unsignedBigInteger('supervisor_id')->nullable()->after('id'); // ID del supervisor
            $table->foreign('supervisor_id')->references('id')->on('empleados')->onDelete('set null'); // Relación a la misma tabla empleados
            $table->enum('estado', ['activo', 'inactivo', 'terminado'])->default('activo')->after('supervisor_id'); // Estado del empleado
        });
    }

    public function down()
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropForeign(['supervisor_id']);
            $table->dropColumn(['supervisor_id', 'estado']);
        });
    }
};
