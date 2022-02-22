<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Asistencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('practicante_id');
            $table->bigInteger('horario_details_id');     
            $table->string('h_entrada')->nullable();
            $table->string('h_salida')->nullable();
            $table->string('retraso');
            $table->string('asist_fecha');
            $table->integer('estado')->default(1);
            $table->string('asist_mes');
            $table->string('asist_anio');           
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
        Schema::dropIfExists('asistencias');
    }
}
