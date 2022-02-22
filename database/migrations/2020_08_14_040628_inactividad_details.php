<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InactividadDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inactividad_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('inactividad_id');
            $table->string('inact_fecha');
            $table->string('inact_mes');
            $table->string('inact_anio');
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
        Schema::dropIfExists('inactividad_details');
    }
}
