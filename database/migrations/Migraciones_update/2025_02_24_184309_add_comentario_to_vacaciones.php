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
            $table->text('comentario')->nullable();  // Agrega el campo comentario como nullable
        });
    }

    public function down()
    {
        Schema::table('vacaciones', function (Blueprint $table) {
            $table->dropColumn('comentario');  // Elimina el campo comentario si revertimos la migración
        });
    }

};
