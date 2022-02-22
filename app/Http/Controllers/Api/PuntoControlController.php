<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PuntoControlController extends Controller
{
    public function create(){
    	return view('PuntoMarcaje');
    }
}