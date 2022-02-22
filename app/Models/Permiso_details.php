<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Permiso;

class Permiso_details extends Model
{
    protected $fillable = ['permiso_id','perm_fecha','perm_mes','perm_anio'];
    public function permiso(){
    	return $this->belongsTo(Permiso::class);
    }
}
