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
            $table->string('foto_perfil')->nullable()->after('tipo_contrato_id');
            $table->string('documento_contrato')->nullable()->after('foto_perfil');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropColumn(['foto_perfil', 'documento_contrato']);
        });
    }

};
