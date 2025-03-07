<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vacaciones', function (Blueprint $table) {
            // Eliminar la columna 'estado' si ya existe
            $table->dropColumn('estado');

            // Agregar la columna 'estado' como enum
            $table->enum('estado', ['pendiente', 'pendientes_aprobacion', 'aprobadas', 'rechazadas'])->default('pendiente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vacaciones', function (Blueprint $table) {
            // Eliminar la columna 'estado'
            $table->dropColumn('estado');

            // Volver a agregar la columna 'estado' como varchar si es necesario para revertir la migración
            $table->string('estado', 255)->default('pendiente');
        });
    }
};
