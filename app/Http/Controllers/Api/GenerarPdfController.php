<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use DateTime;
use DateInterval;

class GenerarPdfController extends Controller
{
    public function getInforme(Request $request){
        $res=[];
        if($request->flag_p){
            if($request->practicante_id == "todo"){
                if($request->tipo=="asistencias"){
                    $fechas=[];
                    $inicio = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
                    if($request->f_date_practicante=="") $final = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
                    else $final =  DateTime::createFromFormat('Y-m-d',$request->f_date_practicante);                    
                    for($i = $inicio; $i <= $final; $i->modify('+1 day')){  
                        array_push($fechas,$i->format('Y-m-d'));
                    }

                    $res = DB::table('asistencias')
                    ->join('practicantes','asistencias.practicante_id','practicantes.id')
                    ->join('horario_details','asistencias.horario_details_id','horario_details.id')
                    ->join('horarios','practicantes.horario_id','horarios.id')
                    ->select('practicantes.id','practicantes.nombre','horario_details.hd_nombre','asistencias.*','horarios.h_nombre')
                    ->where('practicantes.estado',1)
                    ->whereIn('asist_fecha',$fechas)
                    ->orderBy('asistencias.asist_fecha','DESC')
                    ->get();
                }
                if($request->tipo=="faltas"){
                    $fechas=[];
                    $inicio = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
                    if($request->f_date_practicante=="") $final = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
                    else $final =  DateTime::createFromFormat('Y-m-d',$request->f_date_practicante);                    
                    for($i = $inicio; $i <= $final; $i->modify('+1 day')){  
                        array_push($fechas,$i->format('Y-m-d'));
                    }
                    $res = DB::table('faltas')
                    ->join('practicantes','faltas.practicante_id','practicantes.id')
                    ->join('horario_details','faltas.horario_details_id','horario_details.id')
                    ->join('horarios','horario_details.horario_id','horarios.id')
                    ->select('practicantes.id','practicantes.nombre','horario_details.hd_nombre','faltas.*','horarios.h_nombre')
                    ->where('practicantes.estado',1)
                    ->whereIn('falta_fecha',$fechas)
                    ->orderBy('faltas.falta_fecha','DESC')
                    ->get();
                }
                if($request->tipo=="permisos"){
                    $fechas=[];
                    $inicio = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
                    if($request->f_date_practicante=="") $final = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
                    else $final =  DateTime::createFromFormat('Y-m-d',$request->f_date_practicante);                    
                    for($i = $inicio; $i <= $final; $i->modify('+1 day')){  
                        array_push($fechas,$i->format('Y-m-d'));
                    }
                    $res = DB::table('permisos')    
                    ->join('permiso_details','permiso_details.permiso_id','permisos.id')  
                    ->join('horario_details','horario_details.id','permiso_details.horario_details_id')    
                    ->join('practicantes','permisos.practicante_id','practicantes.id')  
                    ->join('horarios','horarios.id','practicantes.horario_id') 
                    ->select('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')   
                    ->where('practicantes.estado',1)
                    ->whereIn('permiso_details.perm_fecha',$fechas)
                    ->groupBy('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')
                    ->orderBy('permisos.id','DESC') 
                    ->get();      
                }
                if($request->tipo=="todo"){
                    $fechas=[];
                    $inicio = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
                    if($request->f_date_practicante=="") $final = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
                    else $final =  DateTime::createFromFormat('Y-m-d',$request->f_date_practicante);                    
                    for($i = $inicio; $i <= $final; $i->modify('+1 day')){  
                        array_push($fechas,$i->format('Y-m-d'));
                    }
                    $res1 = DB::table('asistencias')
                    ->join('practicantes','asistencias.practicante_id','practicantes.id')
                    ->join('horario_details','asistencias.horario_details_id','horario_details.id')
                    ->join('horarios','practicantes.horario_id','horarios.id')
                    ->select('practicantes.id','practicantes.nombre','horario_details.hd_nombre','asistencias.*','horarios.h_nombre')
                    ->where('practicantes.estado',1)
                    ->whereIn('asist_fecha',$fechas)
                    ->orderBy('asistencias.asist_fecha','DESC')
                    ->get();
                    $res2 = DB::table('faltas')
                    ->join('practicantes','faltas.practicante_id','practicantes.id')
                    ->join('horario_details','faltas.horario_details_id','horario_details.id')
                    ->join('horarios','horario_details.horario_id','horarios.id')
                    ->select('practicantes.id','practicantes.nombre','horario_details.hd_nombre','faltas.*','horarios.h_nombre')
                    ->where('practicantes.estado',1)
                    ->whereIn('falta_fecha',$fechas)
                    ->orderBy('faltas.falta_fecha','DESC')
                    ->get();
                    $res3 = DB::table('permisos')    
                    ->join('permiso_details','permiso_details.permiso_id','permisos.id')  
                    ->join('horario_details','horario_details.id','permiso_details.horario_details_id')    
                    ->join('practicantes','permisos.practicante_id','practicantes.id')  
                    ->join('horarios','horarios.id','practicantes.horario_id') 
                    ->select('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')   
                    ->where('practicantes.estado',1)
                    ->whereIn('permiso_details.perm_fecha',$fechas)
                    ->groupBy('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')
                    ->orderBy('permisos.id','DESC') 
                    ->get(); 
                    $res = [$res1,$res2,$res3]; 
                } 
            }         
            else{
                if($request->tipo=="asistencias"){
                    $fechas=[];
                    $inicio = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
                    if($request->f_date_practicante=="") $final = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
                    else $final =  DateTime::createFromFormat('Y-m-d',$request->f_date_practicante);                    
                    for($i = $inicio; $i <= $final; $i->modify('+1 day')){  
                        array_push($fechas,$i->format('Y-m-d'));
                    }

                    $res = DB::table('asistencias')
                    ->join('practicantes','asistencias.practicante_id','practicantes.id')
                    ->join('horario_details','asistencias.horario_details_id','horario_details.id')
                    ->join('horarios','practicantes.horario_id','horarios.id')
                    ->select('practicantes.id','practicantes.nombre','horario_details.hd_nombre','asistencias.*','horarios.h_nombre')
                    ->where('practicantes.id',$request->practicante_id)
                    ->whereIn('asist_fecha',$fechas)
                    ->orderBy('asistencias.asist_fecha','DESC')
                    ->get();

                }
                if($request->tipo=="faltas"){
                    $fechas=[];
                    $inicio = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
                    if($request->f_date_practicante=="") $final = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
                    else $final =  DateTime::createFromFormat('Y-m-d',$request->f_date_practicante);                    
                    for($i = $inicio; $i <= $final; $i->modify('+1 day')){  
                        array_push($fechas,$i->format('Y-m-d'));
                    }

                    $res = DB::table('faltas')
                    ->join('practicantes','faltas.practicante_id','practicantes.id')
                    ->join('horario_details','faltas.horario_details_id','horario_details.id')
                    ->join('horarios','horario_details.horario_id','horarios.id')
                    ->select('practicantes.id','practicantes.nombre','horario_details.hd_nombre','faltas.*','horarios.h_nombre')
                    ->where('practicantes.id',$request->practicante_id)
                    ->whereIn('falta_fecha',$fechas)
                    ->orderBy('faltas.falta_fecha','DESC')
                    ->get();
                }
                if($request->tipo=="permisos"){
                    $fechas=[];
                    $inicio = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
                    if($request->f_date_practicante=="") $final = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
                    else $final =  DateTime::createFromFormat('Y-m-d',$request->f_date_practicante);                    
                    for($i = $inicio; $i <= $final; $i->modify('+1 day')){  
                        array_push($fechas,$i->format('Y-m-d'));
                    }
                    $res = DB::table('permisos')    
                    ->join('permiso_details','permiso_details.permiso_id','permisos.id')  
                    ->join('horario_details','horario_details.id','permiso_details.horario_details_id')    
                    ->join('practicantes','permisos.practicante_id','practicantes.id')  
                    ->join('horarios','horarios.id','practicantes.horario_id') 
                    ->select('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')   
                    ->where('practicantes.id',$request->practicante_id)
                    ->whereIn('permiso_details.perm_fecha',$fechas)
                    ->groupBy('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')
                    ->orderBy('permisos.id','DESC') 
                    ->get();  
                }   
                if($request->tipo=="todo"){
                    $fechas=[];
                    $inicio = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
                    if($request->f_date_practicante=="") $final = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
                    else $final =  DateTime::createFromFormat('Y-m-d',$request->f_date_practicante);                    
                    for($i = $inicio; $i <= $final; $i->modify('+1 day')){  
                        array_push($fechas,$i->format('Y-m-d'));
                    }
                    $res1 = DB::table('asistencias')
                    ->join('practicantes','asistencias.practicante_id','practicantes.id')
                    ->join('horario_details','asistencias.horario_details_id','horario_details.id')
                    ->join('horarios','practicantes.horario_id','horarios.id')
                    ->select('practicantes.id','practicantes.nombre','horario_details.hd_nombre','asistencias.*','horarios.h_nombre')
                    ->where('practicantes.id',$request->practicante_id)
                    ->whereIn('asist_fecha',$fechas)
                    ->orderBy('asistencias.asist_fecha','DESC')
                    ->get();
                    $res2 = DB::table('faltas')
                    ->join('practicantes','faltas.practicante_id','practicantes.id')
                    ->join('horario_details','faltas.horario_details_id','horario_details.id')
                    ->join('horarios','horario_details.horario_id','horarios.id')
                    ->select('practicantes.id','practicantes.nombre','horario_details.hd_nombre','faltas.*','horarios.h_nombre')
                    ->where('practicantes.id',$request->practicante_id)
                    ->whereIn('falta_fecha',$fechas)
                    ->orderBy('faltas.falta_fecha','DESC')
                    ->get();
                    $res3 = DB::table('permisos')    
                    ->join('permiso_details','permiso_details.permiso_id','permisos.id')  
                    ->join('horario_details','horario_details.id','permiso_details.horario_details_id')    
                    ->join('practicantes','permisos.practicante_id','practicantes.id')  
                    ->join('horarios','horarios.id','practicantes.horario_id') 
                    ->select('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')   
                    ->where('practicantes.id',$request->practicante_id)
                    ->whereIn('permiso_details.perm_fecha',$fechas)
                    ->groupBy('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')
                    ->orderBy('permisos.id','DESC') 
                    ->get(); 
                    $res = [$res1,$res2,$res3]; 
                }           
            }
        }
        if($request->flag_i){
            $fechas=[];
            $inicio = DateTime::createFromFormat('Y-m-d',$request->i_date_inactividad);
            if($request->f_date_inactividad=="") $final = DateTime::createFromFormat('Y-m-d',$request->i_date_inactividad);
            else $final =  DateTime::createFromFormat('Y-m-d',$request->f_date_inactividad);                    
            for($i = $inicio; $i <= $final; $i->modify('+1 day')){  
                array_push($fechas,$i->format('Y-m-d'));
            }
            $res = DB::table('inactividades')
            ->join('inactividad_details','inactividad_details.inactividad_id','inactividades.id')
            ->select('inactividades.justificante','inactividades.fecha_inicio','inactividades.fecha_final')
            ->groupBy('inactividades.justificante','inactividades.fecha_inicio','inactividades.fecha_final')
            ->whereIn('inactividad_details.inact_fecha',$fechas)
            ->get();
        }
        return $res;
    }

    public function nroAsistenciasPracticante(Request $request){  
        $fechas=[];
        $inicio = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
        if($request->f_date_practicante=="") $final = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
        else $final =  DateTime::createFromFormat('Y-m-d',$request->f_date_practicante);                    
        for($i = $inicio; $i <= $final; $i->modify('+1 day')){  
            array_push($fechas,$i->format('Y-m-d'));
        }
        $asistencias = DB::table('asistencias')
        ->join('practicantes','asistencias.practicante_id','practicantes.id')
        ->join('horario_details','asistencias.horario_details_id','horario_details.id')
        ->join('horarios','practicantes.horario_id','horarios.id')
        ->select('practicantes.id','practicantes.nombre','horario_details.hd_nombre','asistencias.*','horarios.h_nombre')
        ->where('practicantes.id',$request->practicante_id)
        ->where('asistencias.estado',1)
        ->whereIn('asist_fecha',$fechas)
        ->orderBy('asistencias.asist_fecha','DESC')
        ->get();
        return response()->json(count($asistencias));
    }  

    public function DiasTrabajados(Request $request){ 
        $fechas=[];
        $inicio = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
        if($request->f_date_practicante=="") $final = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
        else $final =  DateTime::createFromFormat('Y-m-d',$request->f_date_practicante);                    
        for($i = $inicio; $i <= $final; $i->modify('+1 day')){  
            array_push($fechas,$i->format('Y-m-d'));
        } 
        $asistencias = DB::table('asistencias')
        ->join('practicantes','asistencias.practicante_id','practicantes.id')
        ->join('horario_details','asistencias.horario_details_id','horario_details.id')
        ->join('horarios','practicantes.horario_id','horarios.id')
        ->select('practicantes.id','practicantes.nombre','horario_details.hd_nombre','asistencias.*','horarios.h_nombre')
        ->where('asistencias.estado',1)
        ->where('practicantes.id',$request->practicante_id)
        ->whereIn('asist_fecha',$fechas)
        ->orderBy('asistencias.asist_fecha')
        ->get();
        
        $q = DB::table('horario_details')
        ->join('horarios','horario_details.horario_id','horarios.id')
        ->join('practicantes','practicantes.horario_id','horarios.id')
        ->where('practicantes.id',$request->practicante_id)->get();
        $nro_turnos=count($q);
        
        $nroDia=0;
        $turno=1;
        $f_ant="";
        $ejm=[];
        foreach($asistencias as $key=>$asistencia){       
            if($nro_turnos==1){
                $nroDia = count($asistencias);
                break;
            }
            array_push($ejm,$asistencia->asist_fecha,$f_ant);
            if($key>0 && $asistencia->asist_fecha==$f_ant){                
                $turno++;               
                if($turno==$nro_turnos){
                    $nroDia++;
                    $turno=1;
                }
            }else{
                $turno=1;
            }
            $f_ant=$asistencia->asist_fecha;
        }
        return response()->json($nroDia);
    } 

    public function totalRetraso(Request $request){  
        $fechas=[];
        $inicio = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
        if($request->f_date_practicante=="") $final = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
        else $final =  DateTime::createFromFormat('Y-m-d',$request->f_date_practicante);                    
        for($i = $inicio; $i <= $final; $i->modify('+1 day')){  
            array_push($fechas,$i->format('Y-m-d'));
        } 
        $retrasos = DB::table('asistencias')
        ->select('retraso')
        ->where('asistencias.practicante_id',$request->practicante_id)
        ->whereIn('asist_fecha',$fechas)
        ->get();

        $total = 0;
        foreach($retrasos as $r) {
            if($r->retraso!=""){
                $parts = explode(":", $r->retraso);
                $total += $parts[2] + $parts[1]*60 + $parts[0]*3600;
            }        
        }   
        
        return response()->json(gmdate("H:i:s", $total));
    } 

    function nroFaltasPracticante(Request $request){ 
        $fechas=[];
        $inicio = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
        if($request->f_date_practicante=="") $final = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
        else $final =  DateTime::createFromFormat('Y-m-d',$request->f_date_practicante);                    
        for($i = $inicio; $i <= $final; $i->modify('+1 day')){  
            array_push($fechas,$i->format('Y-m-d'));
        }  
        $faltas = DB::table('faltas')
        ->join('practicantes','faltas.practicante_id','practicantes.id')
        ->join('horario_details','faltas.horario_details_id','horario_details.id')
        ->join('horarios','horario_details.horario_id','horarios.id')
        ->select('practicantes.id','practicantes.nombre','horario_details.hd_nombre','faltas.*','horarios.h_nombre')
        ->where('practicantes.id',$request->practicante_id)
        ->whereIn('falta_fecha',$fechas)
        ->orderBy('faltas.falta_fecha','DESC')
        ->get();
        return response()->json(count($faltas));
    }  

    public function nroPermisosPracticante(Request $request){
        $fechas=[];
        $inicio = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
        if($request->f_date_practicante=="") $final = DateTime::createFromFormat('Y-m-d',$request->i_date_practicante);
        else $final =  DateTime::createFromFormat('Y-m-d',$request->f_date_practicante);                    
        for($i = $inicio; $i <= $final; $i->modify('+1 day')){  
            array_push($fechas,$i->format('Y-m-d'));
        }
        $permisos = DB::table('permisos')    
        ->join('permiso_details','permiso_details.permiso_id','permisos.id')  
        ->join('horario_details','horario_details.id','permiso_details.horario_details_id')    
        ->join('practicantes','permisos.practicante_id','practicantes.id')  
        ->join('horarios','horarios.id','practicantes.horario_id') 
        ->select('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')   
        ->where('practicantes.id',$request->practicante_id)
        ->whereIn('permiso_details.perm_fecha',$fechas)
        ->groupBy('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')
        ->orderBy('permisos.id','DESC') 
        ->get(); 

        return response()->json(count($permisos));
    }
}
