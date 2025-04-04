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
        Schema::table('permisos', function (Blueprint $table) {
            $table->date('reintegro')->nullable();
        });
    }

    public function down()
    {
        Schema::table('permisos', function (Blueprint $table) {
            $table->dropColumn(['reintegro']);
        });
    }

};
