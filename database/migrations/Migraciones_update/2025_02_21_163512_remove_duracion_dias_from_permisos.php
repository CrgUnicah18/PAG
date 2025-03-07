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
        Schema::table('permisos', function (Blueprint $table) {
            // Elimina el campo 'duracion_dias'
            $table->dropColumn('duracion_dias');

            // Agrega el campo 'estado'
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permisos', function (Blueprint $table) {
            // Si es necesario revertir, podemos agregar el campo 'duracion_dias' nuevamente
            $table->integer('duracion_dias')->nullable();

            // También revertimos el campo 'estado'
            $table->dropColumn('estado');
        });
    }
};
