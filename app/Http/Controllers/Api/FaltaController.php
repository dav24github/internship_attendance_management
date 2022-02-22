<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Model\Falta;
use DB;
use DateTime;
use DateInterval;

class FaltaController extends Controller
{
    public function faltasStore()
    {
        $practicantes = DB::table('practicantes')
            ->where('practicantes.estado', 1)
            ->orderBy('practicantes.f_ingreso', 'ASC')->get();

        $check_falta = "";
        foreach ($practicantes as $practicante) {
            $final =  DateTime::createFromFormat('Y-m-d', date('Y-m-d'))->modify('-1 day');
            $inicio = DateTime::createFromFormat('Y-m-d', $final->format('Y-m-d'));
            if ($inicio > DateTime::createFromFormat('Y-m-d', date("Y") . "-01-01")) {

                $check_falta = DB::table('check_faltas')
                    ->where('chk_fecha', $inicio->format('Y-m-d'))->get()->first();
                if ($check_falta) {
                    return response()->json(['success' => 'Ya está actualizado']);
                }

                while (!$check_falta) {
                    if ($inicio <= DateTime::createFromFormat('Y-m-d',  date("Y") . "-01-01")) {
                        break;
                    }
                    $inicio = $inicio->modify('-1 day');
                    $check_falta = DB::table('check_faltas')
                        ->where('chk_fecha', $inicio->format('Y-m-d'))->get()->first();
                }
                for ($i = $inicio; $i <= $final; $i->modify('+1 day')) {
                    if ($i->format("D") != "Sat" && $i->format("D") != "Sun") {
                        $inactividad = DB::table('inactividad_details')
                            ->where('inactividad_details.inact_fecha', $i->format("Y-m-d"))->get()->first();

                        if (!$inactividad) {
                            $horario_turnos = DB::table('horario_details')
                                ->join('horarios', 'horarios.id', 'horario_details.horario_id')
                                ->join('practicantes', 'horarios.id', 'practicantes.horario_id')
                                ->select('horario_details.id', 'horarios.h_nombre', 'horario_details.hd_nombre')
                                ->where('practicantes.id', $practicante->id)->get();

                            foreach ($horario_turnos as $turno) {
                                $datos_asistencia = DB::table('asistencias')
                                    ->join('practicantes', 'practicantes.id', 'asistencias.practicante_id')
                                    ->select('asistencias.*')
                                    ->where('asistencias.asist_fecha', $i->format("Y-m-d"))
                                    ->where('asistencias.horario_details_id', $turno->id)
                                    ->where('practicantes.id', $practicante->id)->get()->first();

                                if ($datos_asistencia) {
                                    if ($datos_asistencia->estado == '0') {
                                        $data = [
                                            'practicante_id' => $practicante->id,
                                            'horario_details_id' => $turno->id,
                                            'falta_fecha' => $i->format('Y-m-d'),
                                            "hr_entrada" => $datos_asistencia->h_entrada,
                                            'falta_mes' => $i->format('F'),
                                            'falta_anio' => $i->format('Y'),
                                            'created_at' => Carbon::now()->toDateTimeString(),
                                            'updated_at' => Carbon::now()->toDateTimeString()
                                        ];
                                        DB::table('faltas')->insert($data);
                                    }
                                } else {
                                    $permiso = DB::table('permisos')
                                        ->join('permiso_details', 'permisos.id', 'permiso_details.permiso_id')
                                        ->where('permisos.practicante_id', $practicante->id)
                                        ->where('permiso_details.horario_details_id', $turno->id)
                                        ->where('permiso_details.perm_fecha', $i->format("Y-m-d"))
                                        ->orderBy('permisos.id', 'DESC')->first();
                                    if ($permiso != null) {
                                        $data = [
                                            'practicante_id' => $practicante->id,
                                            'horario_details_id' => $turno->id,
                                            'h_entrada' => "",
                                            'h_salida' => "",
                                            'retraso' => "",
                                            'asist_fecha' => $i->format('Y-m-d'),
                                            'estado' => 3,
                                            'asist_mes' => $i->format('F'),
                                            'asist_anio' => $i->format('Y'),
                                            'created_at' => Carbon::now()->toDateTimeString(),
                                            'updated_at' => Carbon::now()->toDateTimeString()
                                        ];
                                        DB::table('asistencias')->insert($data);
                                    } else {
                                        $data = [
                                            'practicante_id' => $practicante->id,
                                            'horario_details_id' => $turno->id,
                                            'falta_fecha' => $i->format('Y-m-d'),
                                            "hr_entrada" => "",
                                            'falta_mes' => $i->format('F'),
                                            'falta_anio' => $i->format('Y'),
                                            'created_at' => Carbon::now()->toDateTimeString(),
                                            'updated_at' => Carbon::now()->toDateTimeString()
                                        ];
                                        DB::table('faltas')->insert($data);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if (!DB::table('check_faltas')->get()->first() || !DB::table('check_faltas')->where('chk_fecha', $final->format('Y-m-d'))->get()->first())
            DB::table('check_faltas')->insert([
                'chk_fecha' => $final->format('Y-m-d'),
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);

        return response()->json(['success' => 'Falta Data Update!']);
    }

    public function getFaltas()
    {
        $faltas = DB::table('faltas')
            ->join('practicantes', 'faltas.practicante_id', 'practicantes.id')
            ->join('horario_details', 'faltas.horario_details_id', 'horario_details.id')
            ->join('horarios', 'horario_details.horario_id', 'horarios.id')
            ->select('practicantes.id', 'practicantes.nombre', 'horario_details.hd_nombre', 'faltas.*', 'horarios.h_nombre')
            ->where('practicantes.estado', '1')
            ->orderBy('faltas.falta_fecha', 'DESC')
            ->paginate(10);
        return $faltas;
    }

    public function indexHistorial()
    {
        $faltas = DB::table('faltas')
            ->join('practicantes', 'faltas.practicante_id', 'practicantes.id')
            ->join('horario_details', 'faltas.horario_details_id', 'horario_details.id')
            ->join('horarios', 'horario_details.horario_id', 'horarios.id')
            ->select('practicantes.id', 'practicantes.nombre', 'horario_details.hd_nombre', 'faltas.*', 'horarios.h_nombre')
            ->orderBy('faltas.falta_fecha', 'DESC')
            ->paginate(10);
        return $faltas;
    }

    public function search()
    {
        if (\Request::get('q') == "")
            return $this->getFaltas();

        if ($search = \Request::get('q')) {
            $faltas = DB::table('faltas')
                ->join('practicantes', 'faltas.practicante_id', 'practicantes.id')
                ->join('horario_details', 'faltas.horario_details_id', 'horario_details.id')
                ->join('horarios', 'horario_details.horario_id', 'horarios.id')
                ->select('practicantes.id', 'practicantes.nombre', 'horario_details.hd_nombre', 'faltas.*', 'horarios.h_nombre')
                ->orderBy('faltas.falta_fecha', 'DESC')
                ->where('practicantes.estado', '1')
                ->where(function ($query) use ($search) {
                    $query->where('practicantes.nombre', 'LIKE', "%$search%")
                        ->orWhere('faltas.falta_fecha', 'LIKE', "%$search%");
                })->paginate(10);
        }

        return $faltas;
    }

    public function searchHistorial()
    {
        if (\Request::get('q') == "")
            return $this->getFaltas();

        if ($search = \Request::get('q')) {
            $faltas = DB::table('faltas')
                ->join('practicantes', 'faltas.practicante_id', 'practicantes.id')
                ->join('horario_details', 'faltas.horario_details_id', 'horario_details.id')
                ->join('horarios', 'horario_details.horario_id', 'horarios.id')
                ->select('practicantes.id', 'practicantes.nombre', 'horario_details.hd_nombre', 'faltas.*', 'horarios.h_nombre')
                ->orderBy('faltas.falta_fecha', 'DESC')
                ->where(function ($query) use ($search) {
                    $query->where('practicantes.nombre', 'LIKE', "%$search%")
                        ->orWhere('faltas.falta_fecha', 'LIKE', "%$search%");
                })->paginate(10);
        }

        return $faltas;
    }

    public function faltaDelete($id)
    {
        DB::table('faltas')->delete($id);
        return response()->json(['success' => 'Falta Data Deleted!']);
    }

    function faltasPracticante($id)
    {
        $faltas = DB::table('faltas')
            ->join('practicantes', 'faltas.practicante_id', 'practicantes.id')
            ->join('horario_details', 'faltas.horario_details_id', 'horario_details.id')
            ->join('horarios', 'horario_details.horario_id', 'horarios.id')
            ->select('practicantes.id', 'practicantes.nombre', 'horario_details.hd_nombre', 'faltas.*', 'horarios.h_nombre')
            ->where('practicantes.id', $id)
            ->orderBy('faltas.falta_fecha', 'DESC')
            ->paginate(10);
        return $faltas;
    }

    function nroFaltasPracticante($id)
    {
        $faltas = DB::table('faltas')
            ->join('practicantes', 'faltas.practicante_id', 'practicantes.id')
            ->join('horario_details', 'faltas.horario_details_id', 'horario_details.id')
            ->join('horarios', 'horario_details.horario_id', 'horarios.id')
            ->select('practicantes.id', 'practicantes.nombre', 'horario_details.hd_nombre', 'faltas.*', 'horarios.h_nombre')
            ->where('practicantes.id', $id)
            ->orderBy('faltas.falta_fecha', 'DESC')
            ->get();
        return response()->json(count($faltas));
    }

    function faltasMonth(Request $request)
    {
        $months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $meses = [];

        if ($request->i_month != "todo") {
            $inicio = (int)$request->i_month;
            if ($request->f_month == "")
                $final = (int)$request->i_month;
            else
                $final = (int)$request->f_month;

            if ($inicio <= $final) {
                for ($i = $inicio; $i <= $final; $i++) {
                    array_push($meses, $months[$i - 1]);
                }
            }
            $faltas_mes = DB::table('faltas')
                ->join('practicantes', 'faltas.practicante_id', 'practicantes.id')
                ->join('horario_details', 'faltas.horario_details_id', 'horario_details.id')
                ->join('horarios', 'horario_details.horario_id', 'horarios.id')
                ->select('practicantes.id', 'practicantes.nombre', 'horario_details.hd_nombre', 'faltas.*', 'horarios.h_nombre')
                ->orderBy('faltas.falta_fecha', 'DESC')
                ->where('practicantes.estado', '1')
                ->whereIn('falta_mes', $meses)->paginate(10);
        } else {
            $faltas_mes = DB::table('faltas')->paginate(10);
        }
        return $faltas_mes;
    }

    function faltasDate(Request $request)
    {
        $faltas_date = DB::table('faltas')
            ->join('practicantes', 'faltas.practicante_id', 'practicantes.id')
            ->join('horario_details', 'faltas.horario_details_id', 'horario_details.id')
            ->join('horarios', 'horario_details.horario_id', 'horarios.id')
            ->select('practicantes.id', 'practicantes.nombre', 'horario_details.hd_nombre', 'faltas.*', 'horarios.h_nombre')
            ->orderBy('faltas.falta_fecha', 'DESC')
            ->where('practicantes.estado', '1')
            ->where('faltas.falta_fecha', $request->date)->paginate(10);

        return $faltas_date;
    }
}