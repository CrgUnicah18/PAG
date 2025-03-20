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
        Schema::table('anuncios', function (Blueprint $table) {
            $table->timestamp('fecha_expiracion')->nullable()->after('fecha_hora'); // O usa 'date' si prefieres solo la fecha
        });
    }

    public function down()
    {
        Schema::table('anuncios', function (Blueprint $table) {
            $table->dropColumn('fecha_expiracion');
        });
    }
};
