<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Horario;
use App\Model\Permiso;

class Practicante extends Model
{
    protected $fillable = ['nombre', 'horario_id', 'ci', 'cu', 'carrera', 'email', 'telefono', 'direccion', 'estado', 'f_ingreso', 'foto'];
    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }
    public function permiso()
    {
        return $this->hasMany(Permiso::class);
    }
}