<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Practicante;
use App\Model\Horario;

class Horario_details extends Model
{
    protected $fillable = ['h_nombre','horario_e','horario_s','tolerancia'];
    public function horario(){
    	return $this->belongsTo(Horario::class);
    }
}

