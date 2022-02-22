<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Faltas extends Migration
{
    public function up()
    {
        Schema::create('faltas', function (Blueprint $table) {
            $table->bigIncrements('id');      
            $table->bigInteger('practicante_id');
            $table->bigInteger('horario_details_id');                     
            $table->string('hr_entrada')->nullable();                 
            $table->string('falta_fecha');     
            $table->string('falta_mes');  
            $table->string('falta_anio');   
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
        Schema::dropIfExists('faltas');
    }
}
