<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Practicante;

class Falta extends Model
{
    protected $fillable = ['practicante_id','falta_fecha','falta_mes','falta_anio'];
    public function practicante(){
    	return $this->belongsTo(Practicante::class);
    }
}
