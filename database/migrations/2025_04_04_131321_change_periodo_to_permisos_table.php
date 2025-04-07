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
        // Paso 1: Hacer la columna 'periodo' nullable temporalmente
        Schema::table('vacaciones', function (Blueprint $table) {
            $table->integer('periodo')->nullable()->change();
        });

        Schema::table('permisos', function (Blueprint $table) {
            $table->integer('periodo')->nullable()->change();
        });

        // Paso 2: Asignar el valor por defecto como el año actual
        DB::table('vacaciones')->whereNull('periodo')->update(['periodo' => date('Y')]);
        DB::table('permisos')->whereNull('periodo')->update(['periodo' => date('Y')]);

        // Paso 3: Hacer la columna 'periodo' NOT NULL
        Schema::table('vacaciones', function (Blueprint $table) {
            $table->integer('periodo')->default(date('Y'))->change();
        });

        Schema::table('permisos', function (Blueprint $table) {
            $table->integer('periodo')->default(date('Y'))->change();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permisos', function (Blueprint $table) {
            //
        });
        Schema::table('vacaciones', function (Blueprint $table) {
            //
        });
    }
};
