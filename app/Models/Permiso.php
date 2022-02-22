<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Permiso_details;
use App\Model\Practicante;

class Permiso extends Model
{
    protected $fillable = ['practicante_id','justificante','fecha_inicio','fecha_final'];
    public function permiso_details(){
        return $this->hasMany(Permiso_details::class);
    }
    public function practicante(){
    	return $this->belongsTo(Practicante::class);
    }
}
