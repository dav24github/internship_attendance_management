<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Practicante;
use App\Model\Horario_details;

class Horario extends Model
{
    protected $fillable = ['h_nombre','horario_e','horario_s','tolerancia'];
    public function practicante(){
        return $this->hasMany(Practicante::class);
    }
    public function horario_details(){
        return $this->hasMany(Horario_details::class);
    }
}

