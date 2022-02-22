<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Model\Asistencia;
use App\Model\Falta;
use App\Model\Marcaje;
use DB;
use DateTime;
use DateInterval;

class AsistenciaController extends Controller
{
    public function asistenciaStoreManual(Request $request)
    {

        $practicante = DB::table('practicantes')->where('id', $request->practicante_id)->get()->first();

        if ($practicante) {
            $hr_e = new DateTime($request->hr_e);
            $hr_s = new DateTime($request->hr_s);

            DB::table('asistencias')->insert([
                "practicante_id" => $practicante->id,
                "horario_details_id" => $request->horario_details_id,
                "h_entrada" => $hr_e->format('H:i:s'),
                "h_salida" => $hr_s->format('H:i:s'),
                "retraso" => "",

                "asist_fecha" => $request->fecha,
                "estado" => 1,

                "asist_mes" => date("F", strtotime($request->fecha)),
                "asist_anio" => date("Y", strtotime($request->fecha)),
                "created_at" =>  Carbon::now()->toDateTimeString(), # new \Datetime()
                "updated_at" => Carbon::now() # new \Datetime()
            ]);


            return response()->json(['success' => 'Add Asistencia']);
        } else {
            return response()->json(['error' => 'Email not register']);
        }
    }

    public function asistenciaStore(Request $request)
    {
        $this->validate($request, ['email' => 'required']);

        $practicante = DB::table('practicantes')->where('email', $request->email)->get()->first();

        if (!$practicante) return response()->json(['error' => 'email']);

        $horario_details = DB::table('horarios')
            ->join('horario_details', 'horarios.id', 'horario_details.horario_id')
            ->join('practicantes', 'horarios.id', 'practicantes.horario_id')
            ->select('horario_details.id', 'horario_details.horario_e', 'horario_details.horario_s', 'horarios.tolerancia')
            ->where('practicantes.email', $request->email)->get();

        $nro_turnos = count($horario_details);

        $ultimaAsistencia = DB::table('asistencias')
            ->where('asistencias.practicante_id', $practicante->id)
            ->orderby('created_at', 'DESC')->take(1)->get();

        if (count($ultimaAsistencia) == 0) {
            $fecha_ultAsistencia = "";
            $estado_ultAsistencia = "";
        } else {
            $fecha_ultAsistencia = $ultimaAsistencia[0]->asist_fecha;
            $estado_ultAsistencia = $ultimaAsistencia[0]->estado;
        }

        if ($fecha_ultAsistencia != date('Y-m-d')) {
            DB::table('marcajes')->where('practicante_id', $practicante->id)->delete();

            DB::table('marcajes')->insert([
                "practicante_id" => $practicante->id,
                "marcaje" => 1,
                "turno" => 0,
                "created_at" =>  Carbon::now()->toDateTimeString(), # new \Datetime()
                "updated_at" => Carbon::now()->toDateTimeString()  # new \Datetime()
            ]);
        }
        $marcaje_datos = DB::table('marcajes')
            ->where('marcajes.practicante_id', $practicante->id)->first();
        if ($marcaje_datos->marcaje <= $nro_turnos * 2) {
            if ($estado_ultAsistencia == 0 && count($ultimaAsistencia) != 0 && $fecha_ultAsistencia == date('Y-m-d')) {
                $asistenciaHr_s = new DateTime();
                $horarioD_s = new DateTime($horario_details[$marcaje_datos->turno]->horario_s);

                if ($asistenciaHr_s > $horarioD_s) {

                    DB::table("asistencias")->where('id', $ultimaAsistencia[0]->id)->update([
                        "h_salida" => date("H:i:s"),
                        "estado" => 1,
                        "updated_at" => Carbon::now()->toDateTimeString()  # new \Datetime()
                    ]);

                    $marcaje = DB::table('marcajes')->where('practicante_id', $practicante->id)->get()->first();

                    DB::table('marcajes')->where('practicante_id', $practicante->id)->insert([
                        "marcaje" => $marcaje->marcaje + 1,
                        "turno" => $marcaje->turno + 1,
                        "created_at" =>  Carbon::now()->toDateTimeString(), # new \Datetime()
                        "updated_at" => Carbon::now()->toDateTimeString()  # new \Datetime()
                    ]);
                } else {
                    return response()->json(['error' => 'salida']);
                }
            } else {
                $asistenciaHr_e = new DateTime();
                $horarioD_e = new DateTime($horario_details[$marcaje_datos->turno]->horario_e);
                $horarioD_e->add(new DateInterval('PT' . $horario_details[$marcaje_datos->turno]->tolerancia . 'M'));

                if ($asistenciaHr_e > $horarioD_e) {
                    $difference = $horarioD_e->diff($asistenciaHr_e);
                    $retraso = $difference->format('%H:%I:%S');
                } else {
                    $retraso = "";
                }

                DB::table("asistencias")->insert([
                    "practicante_id" => $practicante->id,
                    "horario_details_id" => $horario_details[$marcaje_datos->turno]->id,
                    "h_entrada" => date("H:i:s"),
                    "h_salida" => "",
                    "retraso" => $retraso,
                    "asist_fecha" => date('Y-m-d'),
                    "estado" => 0,
                    "asist_mes" => date("F", strtotime(date('Y-m-d'))),
                    "asist_anio" => date("Y", strtotime(date('Y-m-d'))),
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString()
                ]);

                $marcaje = DB::table('marcajes')->where('practicante_id', $practicante->id)->get()->first();

                DB::table('marcajes')->where('practicante_id', $practicante->id)->update([
                    "marcaje" => $marcaje->marcaje + 1,
                    "updated_at" => Carbon::now()->toDateTimeString()  # new \Datetime()
                ]);
            }
        } else {
            return response()->json(['error' => 'hoy']);
        }
    }

    public function show($id)
    {
        $asistencia = DB::table('asistencias')->where('id', $id)->get()->first();
        return response()->json($asistencia);
    }

    public function todayAsistencias()
    {
        $asistencias = DB::table('asistencias')
            ->join('practicantes', 'asistencias.practicante_id', 'practicantes.id')
            ->join('horario_details', 'asistencias.horario_details_id', 'horario_details.id')
            ->join('horarios', 'practicantes.horario_id', 'horarios.id')
            ->select('practicantes.id', 'practicantes.nombre', 'horario_details.hd_nombre', 'asistencias.*', 'horarios.h_nombre')
            ->where('practicantes.estado', '1')
            ->where('asistencias.asist_fecha', date('Y-m-d'))
            ->orderBy('asistencias.updated_at', 'DESC')
            ->paginate(10);
        return $asistencias;
    }

    public function getAsistencias()
    {
        $asistencias = DB::table('asistencias')
            ->join('practicantes', 'asistencias.practicante_id', 'practicantes.id')
            ->join('horario_details', 'asistencias.horario_details_id', 'horario_details.id')
            ->join('horarios', 'practicantes.horario_id', 'horarios.id')
            ->select('practicantes.id', 'practicantes.nombre', 'horario_details.hd_nombre', 'asistencias.*', 'horarios.h_nombre')
            ->where('practicantes.estado', '1')
            ->orderBy('asistencias.updated_at', 'DESC')
            ->paginate(10);
        return $asistencias;
    }

    public function indexHistorial()
    {
        $asistencias = DB::table('asistencias')
            ->join('practicantes', 'asistencias.practicante_id', 'practicantes.id')
            ->join('horario_details', 'asistencias.horario_details_id', 'horario_details.id')
            ->join('horarios', 'practicantes.horario_id', 'horarios.id')
            ->select('practicantes.id', 'practicantes.nombre', 'horario_details.hd_nombre', 'asistencias.*', 'horarios.h_nombre')
            ->orderBy('asistencias.updated_at', 'DESC')
            ->paginate(10);
        return $asistencias;
    }

    public function search()
    {
        if (\Request::get('q') == "")
            return $this->getAsistencias();

        if ($search = \Request::get('q')) {
            $asistencias = DB::table('asistencias')
                ->join('practicantes', 'asistencias.practicante_id', 'practicantes.id')
                ->join('horario_details', 'asistencias.horario_details_id', 'horario_details.id')
                ->join('horarios', 'practicantes.horario_id', 'horarios.id')
                ->select('practicantes.id', 'practicantes.nombre', 'horario_details.hd_nombre', 'asistencias.*', 'horarios.h_nombre')
                ->orderBy('asistencias.updated_at', 'DESC')
                ->where('practicantes.estado', '1')
                ->where(function ($query) use ($search) {
                    $query->where('practicantes.nombre', 'LIKE', "%$search%")
                        ->orWhere('asistencias.asist_fecha', 'LIKE', "%$search%");
                })->paginate(10);
        }

        return $asistencias;
    }


    public function searchHistorial()
    {

        if (\Request::get('q') == "")
            return $this->getAsistencias();

        if ($search = \Request::get('q')) {
            $asistencias = DB::table('asistencias')
                ->join('practicantes', 'asistencias.practicante_id', 'practicantes.id')
                ->join('horario_details', 'asistencias.horario_details_id', 'horario_details.id')
                ->join('horarios', 'practicantes.horario_id', 'horarios.id')
                ->select('practicantes.id', 'practicantes.nombre', 'horario_details.hd_nombre', 'asistencias.*', 'horarios.h_nombre')
                ->orderBy('asistencias.updated_at', 'DESC')
                ->where(function ($query) use ($search) {
                    $query->where('practicantes.nombre', 'LIKE', "%$search%")
                        ->orWhere('asistencias.asist_fecha', 'LIKE', "%$search%");
                })->paginate(10);
        }

        return $asistencias;
    }

    public function asistenciaDelete($id)
    {
        $asistencia = DB::table('asistencias')
            ->join('practicantes', 'asistencias.practicante_id', 'practicantes.id')
            ->join('horario_details', 'asistencias.horario_details_id', 'horario_details.id')
            ->join('horarios', 'practicantes.horario_id', 'horarios.id')
            ->select('practicantes.nombre', 'horario_details.hd_nombre', 'asistencias.*', 'horarios.h_nombre')
            ->where('asistencias.id', $id)
            ->orderBy('asistencias.asist_fecha', 'DESC')
            ->get()->first();

        DB::table("faltas")->insert([
            "practicante_id" => $asistencia->practicante_id,
            "horario_details_id" => $asistencia->horario_details_id,
            "hr_entrada" => '',
            "falta_fecha" => $asistencia->asist_fecha,
            "falta_mes" => date("F", strtotime(date($asistencia->asist_fecha))),
            "falta_anio" => date("Y", strtotime(date($asistencia->asist_fecha))),
            "created_at" => Carbon::now()->toDateTimeString(), # new \Datetime()
            "updated_at" => Carbon::now() # new \Datetime())
        ]);


        DB::table('asistencias')->delete($id);
        return response()->json(['success' => 'Asistencia Data Deleted!']);
    }

    public function asistenciaMonth(Request $request)
    {
        $this->validate($request, ['month' => 'required']);
        $mes = $request->month;
        if ($mes != "todo") {
            $asistencias = DB::table('asistencias')
                ->join('practicantes', 'asistencias.practicante_id', 'practicantes.id')
                ->select('practicantes.nombre', 'asistencias.*')
                ->where('practicantes.estado', '1')
                ->where('asistencias.asist_mes', $mes)->paginate(10);
        } else {
            $asistencias = DB::table('asistencias')
                ->join('practicantes', 'asistencias.practicante_id', 'practicantes.id')
                ->select('practicantes.nombre', 'asistencias.*')
                ->where('practicantes.estado', '1')->paginate(10);
        }
        return $asistencias;
    }

    public function asistenciaDate(Request $request)
    {
        $asistencias = DB::table('asistencias')
            ->join('practicantes', 'asistencias.practicante_id', 'practicantes.id')
            ->join('horario_details', 'asistencias.horario_details_id', 'horario_details.id')
            ->join('horarios', 'practicantes.horario_id', 'horarios.id')
            ->select('practicantes.id', 'practicantes.nombre', 'horario_details.hd_nombre', 'asistencias.*', 'horarios.h_nombre')
            ->where('asistencias.asist_fecha', $request->date)
            ->where('practicantes.estado', '1')
            ->orderBy('asistencias.asist_fecha', 'DESC')
            ->paginate(10);

        return $asistencias;
    }


    public function asistenciasPracticante($id)
    {
        $asistencias = DB::table('asistencias')
            ->join('practicantes', 'asistencias.practicante_id', 'practicantes.id')
            ->join('horario_details', 'asistencias.horario_details_id', 'horario_details.id')
            ->join('horarios', 'practicantes.horario_id', 'horarios.id')
            ->select('practicantes.id', 'practicantes.nombre', 'horario_details.hd_nombre', 'asistencias.*', 'horarios.h_nombre')
            ->where('practicantes.id', $id)
            ->orderBy('asistencias.asist_fecha', 'DESC')
            ->paginate(10);
        return $asistencias;
    }

    public function nroAsistenciasPracticante($id)
    {
        $asistencias = DB::table('asistencias')
            ->join('practicantes', 'asistencias.practicante_id', 'practicantes.id')
            ->join('horario_details', 'asistencias.horario_details_id', 'horario_details.id')
            ->join('horarios', 'practicantes.horario_id', 'horarios.id')
            ->select('practicantes.id', 'practicantes.nombre', 'horario_details.hd_nombre', 'asistencias.*', 'horarios.h_nombre')
            ->where('practicantes.id', $id)
            ->where('asistencias.estado', 1)
            ->orderBy('asistencias.asist_fecha', 'DESC')
            ->get();
        return response()->json(count($asistencias));
    }

    public function DiasTrabajados($id)
    {
        $asistencias = DB::table('asistencias')
            ->join('practicantes', 'asistencias.practicante_id', 'practicantes.id')
            ->join('horario_details', 'asistencias.horario_details_id', 'horario_details.id')
            ->join('horarios', 'practicantes.horario_id', 'horarios.id')
            ->select('practicantes.id', 'practicantes.nombre', 'horario_details.hd_nombre', 'asistencias.*', 'horarios.h_nombre')
            ->where('asistencias.estado', 1)
            ->where('practicantes.id', $id)
            ->orderBy('asistencias.asist_fecha')
            ->get();

        $q = DB::table('horario_details')
            ->join('horarios', 'horario_details.horario_id', 'horarios.id')
            ->join('practicantes', 'practicantes.horario_id', 'horarios.id')
            ->where('practicantes.id', $id)->get();
        $nro_turnos = count($q);

        $nroDia = 0;
        $turno = 1;
        $f_ant = "";
        $ejm = [];
        foreach ($asistencias as $key => $asistencia) {
            if ($nro_turnos == 1) {
                $nroDia = count($asistencias);
                break;
            }
            array_push($ejm, $asistencia->asist_fecha, $f_ant);
            if ($key > 0 && $asistencia->asist_fecha == $f_ant) {
                $turno++;
                if ($turno == $nro_turnos) {
                    $nroDia++;
                    $turno = 1;
                }
            } else {
                $turno = 1;
            }
            $f_ant = $asistencia->asist_fecha;
        }
        return response()->json($nroDia);
    }

    public function totalRetraso($id)
    {
        $retrasos = DB::table('asistencias')
            ->select('retraso')
            ->where('asistencias.practicante_id', $id)
            ->get();

        $total = 0;
        foreach ($retrasos as $r) {
            if ($r->retraso != "") {
                $parts = explode(":", $r->retraso);
                $total += $parts[2] + $parts[1] * 60 + $parts[0] * 3600;
            }
        }

        return response()->json(gmdate("H:i:s", $total));
    }

    public function allRetrasos()
    {
        $practicantes = DB::table('practicantes')
            ->where('estado', 1)->get();

        $data = [];
        foreach ($practicantes as $practicante) {
            $retrasos = DB::table('asistencias')
                ->select('retraso')
                ->where('asistencias.practicante_id', $practicante->id)
                ->get();

            $total = 0;
            foreach ($retrasos as $r) {
                if ($r->retraso != "") {
                    $parts = explode(":", $r->retraso);
                    $total += $parts[2] + $parts[1] * 60 + $parts[0] * 3600;
                }
            }
            $d = [
                'nombre' => $practicante->nombre,
                'totalRetrasos' => gmdate("H:i:s", $total)
            ];
            array_push($data, $d);
        }

        return response()->json($data);
    }

    public function retrasoDate(Request $request)
    {
        $practicantes = DB::table('practicantes')
            ->where('estado', 1)->get();

        $fechas = [];

        $inicio = DateTime::createFromFormat('Y-m-d', $request->i_date);
        if ($request->f_date == "") $final = DateTime::createFromFormat('Y-m-d', $request->i_date);
        else $final =  DateTime::createFromFormat('Y-m-d', $request->f_date);

        for ($i = $inicio; $i <= $final; $i->modify('+1 day')) {
            array_push($fechas, $i->format('Y-m-d'));
        }
        $data = [];
        foreach ($practicantes as $practicante) {
            $retrasos = DB::table('asistencias')
                ->select('retraso')
                ->whereIn('asist_fecha', $fechas)
                ->where('asistencias.practicante_id', $practicante->id)
                ->get();

            $total = 0;
            foreach ($retrasos as $r) {
                if ($r->retraso != "") {
                    $parts = explode(":", $r->retraso);
                    $total += $parts[2] + $parts[1] * 60 + $parts[0] * 3600;
                }
            }
            $d = [
                'nombre' => $practicante->nombre,
                'totalRetrasos' => gmdate("H:i:s", $total)
            ];
            array_push($data, $d);
        }

        return response()->json($data);
    }

    public function retrasoMonth(Request $request)
    {
        $practicantes = DB::table('practicantes')
            ->where('estado', 1)->get();

        $data = [];

        if ($request->month == "todo") return $this->allRetrasos();

        foreach ($practicantes as $practicante) {
            $retrasos = DB::table('asistencias')
                ->select('retraso')
                ->where('asist_mes', $request->month)
                ->where('asistencias.practicante_id', $practicante->id)
                ->get();

            $total = 0;
            foreach ($retrasos as $r) {
                if ($r->retraso != "") {
                    $parts = explode(":", $r->retraso);
                    $total += $parts[2] + $parts[1] * 60 + $parts[0] * 3600;
                }
            }
            $d = [
                'nombre' => $practicante->nombre,
                'totalRetrasos' => gmdate("H:i:s", $total)
            ];
            array_push($data, $d);
        }

        return response()->json($data);
    }
}