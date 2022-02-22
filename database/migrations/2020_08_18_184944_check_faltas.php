<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CheckFaltas extends Migration
{
    public function up()
    {
        Schema::create('check_faltas', function (Blueprint $table) {
            $table->bigIncrements('id');                     
            $table->string('chk_fecha');      
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
        Schema::dropIfExists('asistencia_horario_details');
    }
}
