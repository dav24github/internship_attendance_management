<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Inactividad;

class Inactividad_details extends Model
{
    protected $fillable = ['inactividad_id','inact_fecha','inact_mes','inact_anio'];
    public function inactividad(){
    	return $this->belongsTo(Inactividad::class);
    }
}
