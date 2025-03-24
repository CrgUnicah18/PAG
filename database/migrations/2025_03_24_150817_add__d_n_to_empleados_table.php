<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->string('dn', 15)->unique()->nullable()->after('vacaciones_restantes'); // Agrega el campo DN con restricción única
            $table->string('dn_file')->nullable()->after('dn'); // Para almacenar el archivo (fotografía o PDF)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropColumn('dn');
            $table->dropColumn('dn_file');
        });
    }
};
