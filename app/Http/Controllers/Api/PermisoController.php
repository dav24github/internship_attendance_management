<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use DateTime;

class PermisoController extends Controller
{
    public function permisoStore(Request $request){
        $this->validate($request,[
            'practicante_id'=>'required',
            'justificante'=>'required',
        ]);
        if($request->horario_details_id!='todo'){
            if($request->fecha_final!=""){
                $comienzo = new DateTime($request->fecha_inicio);
                $final = new DateTime($request->fecha_final);

                $data=[];
                $data['practicante_id']=$request->practicante_id;           
                $data['justificante']=$request->justificante;
                $data['fecha_inicio']=$comienzo->format('Y-m-d');
                $data['fecha_final']=$final->format('Y-m-d');
                $permiso_id = DB::table('permisos')->insertGetId($data);

                for($i = $comienzo; $i <= $final; $i->modify('+1 day')){            
                    $permisoData=[];
                    $permisoData['permiso_id']=$permiso_id;                         
                    $permisoData['horario_details_id']=$request->horario_details_id;
                    $permisoData['perm_fecha']=$i->format("Y-m-d");
                    $permisoData['perm_mes']=date("F", strtotime($i->format('Y-m-d')));
                    $permisoData['perm_anio']=date("Y", strtotime($i->format('Y-m-d')));
                    DB::table('permiso_details')->insert($permisoData);
                }
            }
            else{
                $f_inicio = new DateTime($request->fecha_inicio);
                $data=[];
                $data['practicante_id']=$request->practicante_id;
                $data['justificante']=$request->justificante;
                $data['fecha_inicio']=$f_inicio->format('Y-m-d');
                $data['fecha_final']="";
                $permiso_id = DB::table('permisos')->insertGetId($data);

                $permisoData=[];
                $permisoData['permiso_id']=$permiso_id;                
                $permisoData['horario_details_id']=$request->horario_details_id;
                $permisoData['perm_fecha']=$f_inicio->format("Y-m-d");
                $permisoData['perm_mes']=date("F", strtotime($f_inicio->format('Y-m-d')));
                $permisoData['perm_anio']=date("Y", strtotime($f_inicio->format('Y-m-d')));
                DB::table('permiso_details')->insert($permisoData);
            }
        }else{
            $horarios = DB::table('horario_details')
            ->join('horarios','horario_details.horario_id','horarios.id')
            ->join('practicantes','practicantes.horario_id','horarios.id')
            ->select('horario_details.id')
            ->where('practicantes.id',$request->practicante_id)->get();
            foreach($horarios as $horario){
                if($request->fecha_final!=""){
                    $comienzo = new DateTime($request->fecha_inicio);
                    $final = new DateTime($request->fecha_final);
    
                    $data=[];
                    $data['practicante_id']=$request->practicante_id;  
                    $data['justificante']=$request->justificante;
                    $data['fecha_inicio']=$comienzo->format('Y-m-d');
                    $data['fecha_final']=$final->format('Y-m-d');
                    $permiso_id = DB::table('permisos')->insertGetId($data);
    
                    for($i = $comienzo; $i <= $final; $i->modify('+1 day')){            
                        $permisoData=[];
                        $permisoData['permiso_id']=$permiso_id;
                        $permisoData['horario_details_id']=$horario->id;
                        $permisoData['perm_fecha']=$i->format("Y-m-d");
                        $permisoData['perm_mes']=date("F", strtotime($i->format('Y-m-d')));
                        $permisoData['perm_anio']=date("Y", strtotime($i->format('Y-m-d')));
                        DB::table('permiso_details')->insert($permisoData);
                    }
                }
                else{
                    $f_inicio = new DateTime($request->fecha_inicio);
                    $data=[];
                    $data['practicante_id']=$request->practicante_id;
                    $data['justificante']=$request->justificante;
                    $data['fecha_inicio']=$f_inicio->format('Y-m-d');
                    $data['fecha_final']="";
                    $permiso_id = DB::table('permisos')->insertGetId($data);
    
                    $permisoData=[];
                    $permisoData['permiso_id']=$permiso_id;
                    $permisoData['horario_details_id']=$horario->id;
                    $permisoData['perm_fecha']=$f_inicio->format("Y-m-d");
                    $permisoData['perm_mes']=date("F", strtotime($f_inicio->format('Y-m-d')));
                    $permisoData['perm_anio']=date("Y", strtotime($f_inicio->format('Y-m-d')));
                    DB::table('permiso_details')->insert($permisoData);
                }
            }
        }
        return response()->json(['success'=>'Add Permiso']);
    }

    public function permiso($id)
    {
        $permiso = DB::table('permisos')
        ->join('permiso_details','permiso_details.permiso_id','permisos.id')  
        ->join('horario_details','horario_details.id','permiso_details.horario_details_id')    
        ->join('practicantes','permisos.practicante_id','practicantes.id')  
        ->join('horarios','horarios.id','practicantes.horario_id') 
        ->select('permisos.practicante_id','permiso_details.horario_details_id','permisos.id','horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')   
        ->where('permisos.id',$id)
        ->get()->first();
        return response()->json($permiso);
    }

    public function permisoUpdate(Request $request, $id)
    {
        $this->validate($request,[    
            'practicante_id'=>'required',        
            'justificante'=>'required',
        ]);
        if($request->horario_details_id!='todo'){
            if($request->fecha_final!=""){
                $comienzo = new DateTime($request->fecha_inicio);
                $final = new DateTime($request->fecha_final);
                
                $data=[];  
                $data['practicante_id']=$request->practicante_id;
                $data['justificante']=$request->justificante;
                $data['fecha_inicio']=$comienzo->format('Y-m-d');
                $data['fecha_final']=$final->format('Y-m-d');
                
                DB::table('permisos')->where('id',$id)->update($data);
                
                $details = DB::table('permiso_details')->where('permiso_id',$id)->get();
                foreach ($details as $detail){
                    DB::table('permiso_details')->delete($detail->id);
                }

                for($i = $comienzo; $i <= $final; $i->modify('+1 day')){            
                    $permisoData=[];
                    $permisoData['permiso_id']=$id;                    
                    $permisoData['horario_details_id']=$request->horario_details_id;
                    $permisoData['perm_fecha']=$i->format("Y-m-d");
                    $permisoData['perm_mes']=date("F", strtotime($i->format('Y-m-d')));
                    $permisoData['perm_anio']=date("Y", strtotime($i->format('Y-m-d')));
                    DB::table('permiso_details')->insert($permisoData);
                }
            }
            else{
                $f_inicio = new DateTime($request->fecha_inicio);
                $data=[];  
                $data['practicante_id']=$request->practicante_id;
                $data['justificante']=$request->justificante;
                $data['fecha_inicio']=$f_inicio->format('Y-m-d');
                $data['fecha_final']="";            
                DB::table('permisos')->where('id',$id)->update($data);
                
                $details = DB::table('permiso_details')->where('permiso_id',$id)->get();
                foreach ($details as $detail){
                    DB::table('permiso_details')->delete($detail->id);
                }

                $permisoData=[];
                $permisoData['permiso_id']=$id;
                $permisoData['horario_details_id']=$request->horario_details_id;
                $permisoData['perm_fecha']=$f_inicio ->format("Y-m-d");
                $permisoData['perm_mes']=date("F", strtotime($f_inicio ->format('Y-m-d')));
                $permisoData['perm_anio']=date("Y", strtotime($f_inicio ->format('Y-m-d')));
                DB::table('permiso_details')->insert($permisoData);
                
            }
        }else{
            $horarios = DB::table('horario_details')
            ->join('horarios','horario_details.horario_id','horarios.id')
            ->join('practicantes','practicantes.horario_id','horarios.id')
            ->select('horario_details.id')
            ->where('practicantes.id',$request->practicante_id)->get();
            foreach($horarios as $horario){
                if($request->fecha_final!=""){
                    $comienzo = new DateTime($request->fecha_inicio);
                    $final = new DateTime($request->fecha_final);
                    
                    $data=[];  
                    $data['practicante_id']=$request->practicante_id;
                    $data['justificante']=$request->justificante;
                    $data['fecha_inicio']=$comienzo->format('Y-m-d');
                    $data['fecha_final']=$final->format('Y-m-d');
                    
                    DB::table('permisos')->where('id',$id)->update($data);
                    
                    $details = DB::table('permiso_details')->where('permiso_id',$id)->get();
                    foreach ($details as $detail){
                        DB::table('permiso_details')->delete($detail->id);
                    }
    
                    for($i = $comienzo; $i <= $final; $i->modify('+1 day')){            
                        $permisoData=[];
                        $permisoData['permiso_id']=$id;
                        $permisoData['horario_details_id']=$horario->id;
                        $permisoData['perm_fecha']=$i->format("Y-m-d");
                        $permisoData['perm_mes']=date("F", strtotime($i->format('Y-m-d')));
                        $permisoData['perm_anio']=date("Y", strtotime($i->format('Y-m-d')));
                        DB::table('permiso_details')->insert($permisoData);
                    }
                }
                else{
                    $f_inicio = new DateTime($request->fecha_inicio);
                    $data=[];  
                    $data['practicante_id']=$request->practicante_id;
                    $data['justificante']=$request->justificante;
                    $data['fecha_inicio']=$f_inicio->format('Y-m-d');
                    $data['fecha_final']="";            
                    DB::table('permisos')->where('id',$id)->update($data);
                    
                    $details = DB::table('permiso_details')->where('permiso_id',$id)->get();
                    foreach ($details as $detail){
                        DB::table('permiso_details')->delete($detail->id);
                    }
    
                    $permisoData=[];
                    $permisoData['permiso_id']=$id;
                    $permisoData['horario_details_id']=$horario->id;
                    $permisoData['perm_fecha']=$f_inicio ->format("Y-m-d");
                    $permisoData['perm_mes']=date("F", strtotime($f_inicio ->format('Y-m-d')));
                    $permisoData['perm_anio']=date("Y", strtotime($f_inicio ->format('Y-m-d')));
                    DB::table('permiso_details')->insert($permisoData);
                    
                }
            }
        }

        return response()->json(['success'=>'Add Permiso']);   
    }

    public function getPermisos(){
        $permisos = DB::table('permisos')
        ->join('permiso_details','permiso_details.permiso_id','permisos.id')  
        ->join('horario_details','horario_details.id','permiso_details.horario_details_id')    
        ->join('practicantes','permisos.practicante_id','practicantes.id')  
        ->join('horarios','horarios.id','practicantes.horario_id') 
        ->select('permisos.id','horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')   
        ->groupBy('permisos.id','horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')
        ->orderBy('permisos.id','DESC') 
        ->paginate(10);
        return $permisos;
    }

    public function search(){        
        if(\Request::get('q') == "")
            return $this->getPermisos();

        if($search = \Request::get('q')){
            $permisos = DB::table('permisos')
            ->join('permiso_details','permiso_details.permiso_id','permisos.id')  
            ->join('horario_details','horario_details.id','permiso_details.horario_details_id')    
            ->join('practicantes','permisos.practicante_id','practicantes.id')  
            ->join('horarios','horarios.id','practicantes.horario_id') 
            ->select('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')   
            ->groupBy('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')
            ->orderBy('permisos.id','DESC') 
            ->where(function($query) use ($search){
                $query->where('practicantes.nombre','LIKE',"%$search%");
            })->paginate(10);
        }        
        return $permisos;
    }

    public function permisoDelete($id){
        $details = DB::table('permiso_details')->where('permiso_id',$id)->get();
        foreach ($details as $detail){
            DB::table('permiso_details')->delete($detail->id);
        }
        DB::table('permisos')->delete($id);
        return response()->json(['success'=>'Permiso & Permiso Details Data Deleted!']);
    }

    public function PermisoMonth(Request $request){
        $this->validate($request,['month'=>'required']);
        $mes = $request->month;
        if($mes!="todo"){
            $permisos = DB::table('permisos') 
            ->join('permiso_details','permiso_details.permiso_id','permisos.id')  
            ->join('horario_details','horario_details.id','permiso_details.horario_details_id')    
            ->join('practicantes','permisos.practicante_id','practicantes.id')  
            ->join('horarios','horarios.id','practicantes.horario_id') 
            ->select('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')   
            ->where('permiso_details.perm_mes',$mes)
            ->groupBy('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')
            ->orderBy('permisos.id','DESC') 
            ->paginate(10);    
        }else{
            $permisos = DB::table('permisos') 
            ->join('permiso_details','permiso_details.permiso_id','permisos.id')  
            ->join('horario_details','horario_details.id','permiso_details.horario_details_id')    
            ->join('practicantes','permisos.practicante_id','practicantes.id')  
            ->join('horarios','horarios.id','practicantes.horario_id') 
            ->select('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')   
            ->groupBy('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')
            ->orderBy('permisos.id','DESC') 
            ->paginate(10);  
        }
        return $permisos;
    }

    public function permisoDate(Request $request){
        $this->validate($request,['date'=>'required']);        
        $permisos = DB::table('permisos') 
            ->join('permiso_details','permiso_details.permiso_id','permisos.id')  
            ->join('horario_details','horario_details.id','permiso_details.horario_details_id')    
            ->join('practicantes','permisos.practicante_id','practicantes.id')  
            ->join('horarios','horarios.id','practicantes.horario_id') 
            ->select('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')   
            ->where('permiso_details.perm_fecha',$request->date)
            ->groupBy('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')
            ->orderBy('permisos.id','DESC') 
            ->paginate(10);
        return $permisos;
    }

    public function permisosPracticante($id){
        $permisos = DB::table('permisos')    
            ->join('permiso_details','permiso_details.permiso_id','permisos.id')  
            ->join('horario_details','horario_details.id','permiso_details.horario_details_id')    
            ->join('practicantes','permisos.practicante_id','practicantes.id')  
            ->join('horarios','horarios.id','practicantes.horario_id') 
            ->select('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')   
            ->where('practicantes.id',$id)
            ->groupBy('horarios.h_nombre','horario_details.hd_nombre','practicantes.nombre','permisos.fecha_inicio','permisos.fecha_final','permisos.justificante')
            ->orderBy('permisos.id','DESC') 
            ->paginate(10);
        return $permisos;
    }

    public function nroPermisosPracticante($id){
        $permisos = DB::table('permisos')
        ->join('practicantes','permisos.practicante_id','practicantes.id')
        ->select('practicantes.id','practicantes.nombre','permisos.*')        
        ->where('practicantes.id',$id)
        ->orderBy('permisos.id','DESC')
        ->get();
        return response()->json(count($permisos));
    }
}
