<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use DB;

class HorarioController extends Controller
{
    public function getHorarios()
    {
        $horarios =  DB::table('horarios')->paginate(10);
        return $horarios;
    }

    public function index()
    {
        $horarios =  DB::table('horarios')->get();
        return $horarios;
    }

    public function search()
    {
        if (\Request::get('q') == "")
            return $this->getHorarios();

        if ($search = \Request::get('q')) {
            $horarios = DB::table("horarios")->where(function ($query) use ($search) {
                $query->where('h_nombre', 'LIKE', "%$search%");
            })->paginate(10);
        }
        return $horarios;
    }

    public function detailsHorario($id)
    {
        $horarios =  DB::table('horarios')
            ->join('horario_details', 'horarios.id', 'horario_details.horario_id')
            ->where('horarios.id', $id)
            ->get();

        $inputs = [];
        $index = 0;
        foreach ($horarios as $horario) {
            $inputs[$index] = [
                'hd_nombre' => $horario->hd_nombre,
                'h_e' => $horario->horario_e,
                'h_s' => $horario->horario_s
            ];
            $index++;
        }

        $details_horario = [
            'h_nombre' => $horarios[0]->h_nombre,
            'tolerancia' => $horarios[0]->tolerancia,
            'inputs' => $inputs,
        ];

        return response()->json($details_horario);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'h_nombre' => 'required',
            'tolerancia' => 'required|numeric'
        ]);

        $h_id = DB::table('horarios')->insertGetId([
            "h_nombre" => $request->h_nombre,
            "tolerancia" => $request->tolerancia,

            "updated_at" => Carbon::now()->toDateTimeString()  # new \Datetime()
        ]);

        $inputs = $request->get('inputs');
        foreach ($inputs as $input) {
            DB::table('horario_details')->insert([
                "horario_id" => $h_id,
                "hd_nombre" => $input['hd_nombre'],
                "horario_e" => $input['h_e'],
                "horario_s" => $input['h_s']
            ]);
        }
        return response()->json(['success' => 'Add Horario']);
    }

    public function show($id)
    {
        $horario = DB::table('horarios')->where('id', $id)->first();
        return response()->json($horario);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'h_nombre' => 'required',
            'tolerancia' => 'required|numeric'
        ]);

        DB::table('horarios')->where('id', $id)->update([
            "h_nombre" => $request->h_nombre,
            "tolerancia" => $request->tolerancia,
            "updated_at" => Carbon::now()->toDateTimeString()  # new \Datetime()
        ]);

        $inputs = $request->get('inputs');

        $id_horario_details = DB::table('horario_details')
            ->join('horarios', 'horario_details.horario_id', 'horarios.id')
            ->select('horario_details.id')
            ->where('horarios.id', $id)->get();

        $index = 0;
        foreach ($inputs as $input) {
            DB::table('horario_details')->where('id', $id_horario_details[$index]->id)->update([
                "hd_nombre" => $input['hd_nombre'],
                "horario_e" => $input['h_e'],
                "horario_s" => $input['h_s'],
                "updated_at" => Carbon::now()->toDateTimeString()  # new \Datetime()
            ]);
            $index++;
        }

        return response()->json(['success' => 'Update Horario']);
    }

    public function destroy($id)
    {
        $details = DB::table('horario_details')->where('horario_id', $id)->get();
        foreach ($details as $detail) {
            DB::table('horario_details')->delete($detail->id);
        }
        DB::table("horarios")->where('id', $id)->delete();
    }

    public function horarioInfo($id)
    {
        $horarios = DB::table('horarios')
            ->where('horarios.id', $id)->first();
        return response()->json($horarios);
    }

    public function horarioDetailsInfo($id)
    {
        $horarioDetails = DB::table('horario_details')
            ->join('horarios', 'horario_details.horario_id', 'horarios.id')
            ->join('practicantes', 'practicantes.horario_id', 'horarios.id')
            ->select('horario_details.id', 'horarios.h_nombre', 'horario_details.hd_nombre', 'horario_details.horario_e', 'horario_details.horario_s')
            ->where('practicantes.id', $id)->get();

        return response()->json($horarioDetails);
    }

    public function horarioDetails($id)
    {
        $horarioDetails = DB::table('horario_details')
            ->where('horario_details.id', $id)->first();
        return response()->json($horarioDetails);
    }

    public function horarioDetailsMore($id)
    {
        $horarioDetails = DB::table('horario_details')
            ->join('horarios', 'horarios.id', 'horario_details.horario_id')
            ->where('horarios.id', $id)->get();
        return response()->json($horarioDetails);
    }
}
