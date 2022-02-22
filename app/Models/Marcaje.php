<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Marcaje extends Model
{
    protected $fillable = ['practicante_id','marcaje','horario_details_id']; 
}
