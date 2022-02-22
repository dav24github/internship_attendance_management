<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermisoDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permiso_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('permiso_id');
            $table->bigInteger('horario_details_id');
            $table->string('perm_fecha');
            $table->string('perm_mes');
            $table->string('perm_anio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permiso_details');
    }
}
