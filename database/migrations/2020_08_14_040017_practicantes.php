<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Practicantes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('practicantes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');            
            $table->bigInteger('horario_id');
            $table->string('ci')->nullable();
            $table->string('cu')->nullable();
            $table->string('carrera')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();            
            $table->string('direccion')->nullable();
            $table->integer('estado')->default(1);
            $table->date('f_ingreso')->nullable();
            $table->string('foto')->nullable();
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
        Schema::dropIfExists('practicantes');
    }
}
