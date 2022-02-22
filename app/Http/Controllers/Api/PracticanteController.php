<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use DB;

class PracticanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $practicantes = DB::table('practicantes')->where('estado', '1')->paginate(10);
        return $practicantes;
    }

    public function indexHistorial()
    {
        $practicantes = DB::table('practicantes')->paginate(10);
        return $practicantes;
    }

    public function getPracticantes()
    { //info
        $practicantes = DB::table('practicantes')->where('estado', '1')->get();
        return response()->json($practicantes);
    }

    public function search()
    {

        if (\Request::get('q') == "")
            return $this->index();

        if ($search = \Request::get('q')) {
            $practicantes = DB::table('practicantes')->where(function ($query) use ($search) {
                $query->where('estado', 1)
                    ->where('nombre', 'LIKE', "%$search%");
            })->paginate(10);
        }

        return $practicantes;
    }

    public function searchHistorial()
    {

        if (\Request::get('q') == "")
            return $this->index();

        if ($search = \Request::get('q')) {
            $practicantes = DB::table('practicantes')->where(function ($query) use ($search) {
                $query->where('nombre', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%");
            })->paginate(2);
        }

        return $practicantes;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|string',
            'horario_id' => 'required',
            'ci' => 'required|unique:practicantes',
            'cu' => 'required|unique:practicantes',
            'carrera' => 'required|string',
            'email' => 'required|email|string|unique:practicantes',
            'telefono' => 'required|numeric|unique:practicantes',
            'direccion' => 'required|string',
            'f_ingreso' => 'required|date',

        ]);

        $imgUrl = "";
        if ($request->file('file')) {
            $imgUrl = "storage/" . $request->file('file')->store('images');
        } else {
            $imgUrl = 'storage/images/user-alt.png';
        }

        DB::table('practicantes')->insert([
            "nombre" => $request->nombre,
            "horario_id" => $request->horario_id,
            "ci" => $request->ci,
            "cu" => $request->cu,
            "carrera" => $request->carrera,
            "email" => $request->email,
            "telefono" => $request->telefono,
            "direccion" => $request->direccion,
            "f_ingreso" => $request->f_ingreso,
            "foto" => $imgUrl
        ]);

        return response()->json(['success' => 'Add Practicante']);
    }

    public function show($id)
    {
        $practicante =  DB::table('practicantes')->where('id', $id)->first();
        return response()->json($practicante);
    }

    public function showPracticante($id)
    {
        $practicante = DB::table('practicantes')
            ->join('horarios', 'practicantes.horario_id', 'horarios.id')
            ->where('practicantes.id', $id)->first();

        $horarios = DB::table('practicantes')
            ->join('horarios', 'practicantes.horario_id', 'horarios.id')
            ->join('horario_details', 'horario_details.horario_id', 'horarios.id')
            ->select('horario_details.hd_nombre', 'horario_details.horario_e', 'horario_details.horario_s')
            ->where('practicantes.id', $id)->get();

        return response()->json([$practicante, $horarios]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nombre' => 'required|string',
            'horario_id' => 'required',
            'ci' => 'required|unique:practicantes,ci,' . $id,
            'cu' => 'required|unique:practicantes,cu,' . $id,
            'carrera' => 'required|string',
            'email' => 'required|email|string|unique:practicantes,email,' . $id,
            'telefono' => 'required|numeric|unique:practicantes,telefono,' . $id,
            'direccion' => 'required|string',
            'f_ingreso' => 'required|date',
        ]);

        $imgUrl = "";
        if ($request->flag == "lleno") {
            $imgUrl = "storage/" . $request->file('file')->store('images');
        } else {
            $p = DB::table('practicantes')->where('id', $id)->get()->first();
            if ($request->flag == "vacio" && $p->foto != "storage/images/user-alt.png") {
                $imgUrl = "storage/images/user-alt.png";
            }
        }

        DB::table('practicantes')->where('id', $id)->update([
            "nombre" => $request->nombre,
            "horario_id" => $request->horario_id,
            "ci" => $request->ci,
            "cu" => $request->cu,
            "carrera" => $request->carrera,
            "email" => $request->email,
            "telefono" => $request->telefono,
            "direccion" => $request->direccion,
            "estado" => $request->estado,
            "f_ingreso" => $request->f_ingreso,
            "foto" => $imgUrl
        ]);

        return response()->json(['success' => 'Update Practicante']);
    }

    public function destroy($id)
    {
        // $practicante = Practicante::where('id',$id)->first();
        // $practicante->estado = 0;
        // $photo = $practicante->foto;
        // if($photo){
        //     unlink($photo);
        //     $practicante->delete();
        // }else{
        //     $practicante->delete();
        // }
        // $practicante->save();
    }
}