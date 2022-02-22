<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Inactividad_details;

class Inactividad extends Model
{
    protected $fillable = ['justificante','fecha_inicio','fecha_final'];
    public function inactividad_details(){
        return $this->hasMany(Inactividad_details::class);
    }
}
