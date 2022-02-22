<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HorarioDetails extends Migration
{
    public function up()
    {
        Schema::create('horario_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('horario_id');
            $table->string('hd_nombre')->nullable();
            $table->time('horario_e');
            $table->time('horario_s');            
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
        Schema::dropIfExists('horario_details');
    }
}
