<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::table('permisos', function (Blueprint $table) {
            $table->string('subsidio_archivo')->nullable();
        });
    }

    public function down()
    {
        Schema::table('permisos', function (Blueprint $table) {
            $table->dropColumn('subsidio_archivo');
        });
    }

};
