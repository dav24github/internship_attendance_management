<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Practicante;

class Asistencia extends Model
{
    protected $fillable = ['practicante_id','h_entrada','h_salida','asist_fecha','estado','asist_mes','asist_anio'];
    public function practicante(){
    	return $this->belongsTo(Practicante::class);
    }
}
