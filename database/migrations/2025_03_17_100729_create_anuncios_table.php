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
        Schema::create('anuncios', function (Blueprint $table) {
            $table->id();
            $table->text('titulo'); // Título del anuncio
            $table->text('contenido'); // Contenido del anuncio
            $table->enum('audiencia', ['empresa', 'todos', 'oficina', 'grupo']); // A quién va dirigido
            $table->dateTime('fecha_hora')->nullable(); // Fecha y hora del anuncio (si es una reunión)
            $table->enum('prioridad', ['alta', 'media', 'baja'])->default('media'); // Prioridad del anuncio
            $table->boolean('activo')->default(true); // Si el anuncio está activo
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anuncios');
    }
};
