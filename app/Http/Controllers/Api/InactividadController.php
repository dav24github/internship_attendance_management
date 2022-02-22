<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use DateTime;

class inactividadController extends Controller
{
    public function inactividadStore(Request $request){
        $this->validate($request,[
            'justificante'=>'required',
        ]);
        
        if($request->fecha_final!=""){
            $comienzo = new DateTime($request->fecha_inicio);
            $final = new DateTime($request->fecha_final);

            $data=[];
            $data['justificante']=$request->justificante;
            $data['fecha_inicio']=$comienzo->format('Y-m-d');
            $data['fecha_final']=$final->format('Y-m-d');
            $inactividad_id = DB::table('inactividades')->insertGetId($data);

            for($i = $comienzo; $i <= $final; $i->modify('+1 day')){            
                $inactividadData=[];
                $inactividadData['inactividad_id']=$inactividad_id;
                $inactividadData['inact_fecha']=$i->format("Y-m-d");
                $inactividadData['inact_mes']=date("F", strtotime($i->format('Y-m-d')));
                $inactividadData['inact_anio']=date("Y", strtotime($i->format('Y-m-d')));
                DB::table('inactividad_details')->insert($inactividadData);
            }
        }
        else{
            $f_inicio = new DateTime($request->fecha_inicio);
            $data=[];
            $data['justificante']=$request->justificante;
            $data['fecha_inicio']=$f_inicio->format('Y-m-d');
            $data['fecha_final']="";
            $inactividad_id = DB::table('inactividades')->insertGetId($data);

            $inactividadData=[];
            $inactividadData['inactividad_id']=$inactividad_id;
            $inactividadData['inact_fecha']=$f_inicio->format("Y-m-d");
            $inactividadData['inact_mes']=date("F", strtotime($f_inicio->format('Y-m-d')));
            $inactividadData['inact_anio']=date("Y", strtotime($f_inicio->format('Y-m-d')));
            DB::table('inactividad_details')->insert($inactividadData);
        }

        return response()->json(['success'=>'Add inactividad']);
    }

    public function show($id)
    {
        $inactividad = DB::table('inactividades')->where('id',$id)->get()->first();
        return response()->json($inactividad);
    }

    public function inactividadUpdate(Request $request, $id)
    {
        $this->validate($request,[            
            'justificante'=>'required',
        ]);
        
        if($request->fecha_final!=""){
            $comienzo = new DateTime($request->fecha_inicio);
            $final = new DateTime($request->fecha_final);

            $data=[];  
            $data['justificante']=$request->justificante;
            $data['fecha_inicio']=$comienzo->format('Y-m-d');
            $data['fecha_final']=$final->format('Y-m-d');
            
            DB::table('inactividades')->where('id',$id)->update($data);
            
            $details = DB::table('inactividad_details')->where('inactividad_id',$id)->get();
            foreach ($details as $detail){
                DB::table('inactividad_details')->delete($detail->id);
            }

            for($i = $comienzo; $i <= $final; $i->modify('+1 day')){            
                $inactividadData=[];
                $inactividadData['inactividad_id']=$id;
                $inactividadData['inact_fecha']=$i->format("Y-m-d");
                $inactividadData['inact_mes']=date("F", strtotime($i->format('Y-m-d')));
                $inactividadData['inact_anio']=date("Y", strtotime($i->format('Y-m-d')));
                DB::table('inactividad_details')->insert($inactividadData);
            }
        }
        else{
            $f_inicio = new DateTime($request->fecha_inicio);
            $data=[];  
            $data['justificante']=$request->justificante;
            $data['fecha_inicio']=$f_inicio->format('Y-m-d');
            $data['fecha_final']="";            
            DB::table('inactividades')->where('id',$id)->update($data);
            
            $details = DB::table('inactividad_details')->where('inactividad_id',$id)->get();
            foreach ($details as $detail){
                DB::table('inactividad_details')->delete($detail->id);
            }

            $inactividadData=[];
            $inactividadData['inactividad_id']=$id;
            $inactividadData['inact_fecha']=$f_inicio ->format("Y-m-d");
            $inactividadData['inact_mes']=date("F", strtotime($f_inicio ->format('Y-m-d')));
            $inactividadData['inact_anio']=date("Y", strtotime($f_inicio ->format('Y-m-d')));
            DB::table('inactividad_details')->insert($inactividadData);
            
        }

        return response()->json(['success'=>'Add inactividad']);   
    }

    public function index(){
        $inactividades = DB::table('inactividades')
            ->orderBy('inactividades.fecha_inicio','ASC')
            ->get();
        return $inactividades;
    }

    public function getInactividades(){
        $inactividades = DB::table('inactividades')
        ->orderBy('inactividades.fecha_inicio','ASC')->paginate(10);
        return $inactividades;
    }

    public function search(){        
        if(\Request::get('q') == "")
            return $this->getInactividades();

        if($search = \Request::get('q')){
            $inactividades = DB::table('inactividades')
            ->orderBy('inactividades.fecha_inicio','ASC')
            ->where(function($query) use ($search){
                $query->where('inactividades.justificante','LIKE',"%$search%");
            })->paginate(10);
        }
        
        return $inactividades;
    }

    public function inactividadDelete($id){
        $details = DB::table('inactividad_details')->where('inactividad_id',$id)->get();
        foreach ($details as $detail){
            DB::table('inactividad_details')->delete($detail->id);
        }
        DB::table('inactividades')->delete($id);
        return response()->json(['success'=>'inactividad &inactividad Details Data Deleted!']);
    }

    public function inactividadMonth(Request $request){
        $this->validate($request,['month'=>'required']);
        $mes = $request->month;
        if($mes!="todo"){
            $inactividades = DB::table('inactividades') 
            ->join('inactividad_details','inactividades.id','inactividad_details.inactividad_id')             
            ->select('inactividades.*')
            ->where('inactividad_details.inact_mes',$mes)->distinct()->paginate(10);        
        }else{
            $inactividades = DB::table('inactividades')->paginate(10);  
        }
        return $inactividades;
    }
}
