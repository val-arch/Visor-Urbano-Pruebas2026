<?php
namespace App\Http\Controllers;

use App\exports\DataExport;
use App\Http\Controllers\Controller;
use App\Models\Municipio;
use App\Traits\ApiResponser;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{
    use ApiResponser;
    public function lineTimeLic()
    {
        $currentUser = Auth::user();
        $id_municipio = $currentUser->id_municipio;

        // Count initial states where no tramite ID is present
        $consulta = DB::table('consulta_requisitos')
            ->leftJoin('tramite', 'consulta_requisitos.folio', '=', 'tramite.folio')
            ->whereNull('tramite.id')
            ->where('consulta_requisitos.id_municipio', $id_municipio)
            ->count();

        // Count where tramites have not been sent to reviewers
        $inicio = DB::table('consulta_requisitos')
            ->leftJoin('tramite', 'consulta_requisitos.folio', '=', 'tramite.folio')
            ->whereNull('tramite.enviado_revisores')
            ->where('consulta_requisitos.id_municipio', $id_municipio)
            ->count();

        // Count where tramites have been sent to reviewers but no PDF license is issued
        $revision = DB::table('consulta_requisitos')
            ->leftJoin('tramite', 'consulta_requisitos.folio', '=', 'tramite.folio')
            ->where('tramite.enviado_revisores', 1)
            ->where('tramite.pdf_licencia', '')
            ->where('consulta_requisitos.id_municipio', $id_municipio)
            ->count();

        // Count emitted licenses
        $emitidas = DB::table('consulta_requisitos')
            ->join('licencias_giro_visor', 'consulta_requisitos.folio', '=', 'licencias_giro_visor.folio')
            ->whereNotNull('licencias_giro_visor.id')
            ->where('consulta_requisitos.id_municipio', $id_municipio)
            ->count();

        // Subquery for counting licenses from the historical table
        $historicCount = DB::table('historico_licencias_giro')
            ->where('id_municipio', $id_municipio)
            ->whereNull('deleted_at')
            ->where('tipo_licencia', 'Refrendo')
            ->count();

        // Adding the historical licenses to the count of emitted licenses
        $totalCount = $emitidas + $historicCount;

        $data = [
            'consulta' => $consulta,
            'inicio' => $inicio,
            'revision' => $revision,
            'emitidas' => $totalCount
        ];
        return $this->successResponse($data);
    }

    public function barraAnual(Request $request)
    {
        $currentUser = Auth::user();
        $id_municipio = $currentUser->id_municipio;
        $formato = [];
        $d = $request->all();
        $year = Carbon::now()->year;
        $def1 = $d['f_inicio'];
        $def2 = $d['f_final'];
        $mes = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        // Obtención de datos del año actual desde licencias_giro_visor
        $data = DB::table('licencias_giro_visor as tra')
        ->join('consulta_requisitos as cons', 'tra.folio', '=', 'cons.folio')
        ->select(DB::raw('COUNT(tra.*) as total'), DB::raw('EXTRACT(MONTH FROM tra.created_at) as mes'))
        ->where('cons.id_municipio', $id_municipio)
        ->whereBetween('tra.created_at', [$def1, $def2]) // use 'tra.created_at' instead of 'licencias_giro_visor.created_at'
        ->whereRaw('EXTRACT(YEAR FROM tra.created_at) = ?', [$year])
        ->groupBy('mes')
        ->get();


        // Obtención de datos históricos
        $historicoData = DB::table('historico_licencias_giro as hist')
            ->select(DB::raw('count(hist.*) as total'), DB::raw('EXTRACT(MONTH FROM hist.created_at) as mes'))
            ->where('hist.id_municipio', $id_municipio)
            ->whereNull('hist.deleted_at') // Añade esta línea para filtrar solo los registros no eliminados
            ->where('hist.tipo_licencia', 'Refrendo')
            ->whereBetween('hist.created_at', [$def1, $def2]) // Añade esta línea para filtrar por tipo de licencia 'Refrendo'
            ->whereRaw('EXTRACT(YEAR FROM hist.created_at) = ?', [$year])
            ->groupBy('mes')
            ->get();

        // Combinación y suma de datos
        $combinedData = [];
        foreach ($data as $d) {
            $combinedData[$d->mes] = $d->total;
        }

        foreach ($historicoData as $hist) {
            if (isset($combinedData[$hist->mes])) {
                $combinedData[$hist->mes] += $hist->total;
            } else {
                $combinedData[$hist->mes] = $hist->total;
            }
        }

        // Preparar el formato final
        foreach ($combinedData as $mesIndex => $totalCount) {
            if ($mesIndex > 0) { // Asegúrate de que el índice del mes sea válido
                array_push($formato, [
                    'name' => $mes[$mesIndex - 1], // -1 porque los arrays en PHP son base 0
                    'value' => $totalCount,
                    'extra' => $mesIndex,
                ]);
            }
        }


        // Devuelve la respuesta
        return response()->json($formato);
    }

    public function barraAnual2()
    {
        $currentUser = Auth::user();

        $id_municipio = $currentUser->id_municipio;
        $formato = [];
        $year = Carbon::now()->year;
        $mes = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $data = DB::select('SELECT count(tra.*), EXTRACT(Month FROM tra.created_at) as mes FROM licencias_giro_visor tra
            join consulta_requisitos cons on tra.folio = cons.folio
            where cons.id_municipio = :municipio and EXTRACT(YEAR FROM tra.created_at) = :yeart group by mes',
            ['municipio' => $id_municipio, 'yeart' => $year]);

        foreach ($data as $key) {
            array_push($formato, array('name' => $mes[$key->mes - 1], 'value' => $key->count, 'extra' => $key->mes));
            // array_push($formato,array('name'=>$mes[$key->mes-1],'value'=>$key->count));
        }
        return $this->successResponse($formato);
    }
    public function getMostUsedScian(Request $request){
    $startDate = $request->query('startDate');
    $endDate = $request->query('endDate');

    // Define the subquery for selecting the top 10 most used SCIAN codes
    $subquery = DB::table('licencias_giro_visor')
        ->select('codigo_scian', DB::raw('COUNT(*) as uso'));

    // Only apply date filter if both dates are provided
    if (!empty($startDate) && !empty($endDate)) {
        $subquery->whereBetween('created_at', [$startDate, $endDate]);
    }

    $subquery->groupBy('codigo_scian')
             ->orderBy('uso', 'desc')
             ->limit(10);

    // Perform the JOIN with the 'public.giros' table and the subquery
    $mostUsedScian = DB::table('public.giros as g')
        ->joinSub($subquery, 't', function ($join) {
            $join->on('g.codigo', '=', 't.codigo_scian');
        })
        ->select('g.codigo', 't.uso', 'g.SCIAN') // Ensure 'SCIAN' is the correct column name
        ->orderBy('t.uso', 'desc')
        ->get();

    return response()->json($mostUsedScian);
}

public function getMostUsedScianbyMunicipio(Request $request){
    $currentUser = Auth::user();
    $municipio_id= $currentUser->id_municipio;
    $startDate = $request->query('startDate');
    $endDate = $request->query('endDate');

    // Define the subquery for selecting the top 10 most used SCIAN codes
    $subquery = DB::table('licencias_giro_visor')->where('municipio_id', $municipio_id)
        ->select('codigo_scian', DB::raw('COUNT(*) as uso'));

    // Only apply date filter if both dates are provided
    if (!empty($startDate) && !empty($endDate)) {
        $subquery->whereBetween('created_at', [$startDate, $endDate]);
    }

    $subquery->groupBy('codigo_scian')
             ->orderBy('uso', 'desc')
             ->limit(10);

    // Perform the JOIN with the 'public.giros' table and the subquery
    $mostUsedScian = DB::table('public.giros as g')
        ->joinSub($subquery, 't', function ($join) {
            $join->on('g.codigo', '=', 't.codigo_scian');
        })
        ->select('g.codigo', 't.uso', 'g.SCIAN') // Ensure 'SCIAN' is the correct column name
        ->orderBy('t.uso', 'desc')
        ->get();

    return response()->json($mostUsedScian);
}



    public function pieRev()
    {
        $currentUser = Auth::user();
        $id_municipio = $currentUser->id_municipio;

        $aprobados = DB::table('revisiones_dependencias')
            ->where('status_actual', 1)
            ->where('rol', 4)
            ->where('id_municipio', $id_municipio)->count();

        $revision = DB::table('revisiones_dependencias')
            ->whereNull('status_actual')
            ->whereIn('rol', [3, 4])
            ->where('id_municipio', $id_municipio)->count();

        $solventaciones = DB::table('revisiones_dependencias')
            ->where('status_actual', 3)
            ->where('id_municipio', $id_municipio)->count();

        $desechadas = DB::table('revisiones_dependencias')
            ->where('status_actual', 2)
            ->where('rol', 4)
            ->where('id_municipio', $id_municipio)->count();

        return $this->successResponse(array('aprobadas' => $aprobados, 'revision' => $revision, 'solventaciones' => $solventaciones, 'desechadas' => $desechadas));
    }

    public function reporteCompleto()
    {

        $array = array();

        $total_emitidas_visor_total = DB::table('licencias_giro_visor')->where('municipio_id', '!=', '1')->whereNull('deleted_at')->get()->count();
        $total_emitidas_visor_refrendo = DB::table('licencias_giro_visor')->where('municipio_id', '!=', '1')->where('tipo_licencia', 'Refrendo')->whereNull('deleted_at')->get()->count();
        $total_emitidas_visor_nuevas = DB::table('licencias_giro_visor')->where('municipio_id', '!=', '1')->where('tipo_licencia', 'Nueva')->whereNull('deleted_at')->get()->count();
        array_push($array, "Total licencias emitidas: " . $total_emitidas_visor_total);
        $total_emitidas_historico = DB::table('historico_licencias_giro')->where('tipo_licencia', 'Refrendo')->where('id_municipio', '!=', '1')->whereNull('deleted_at')->get()->count();
        array_push($array, "Total licencias emitidas histórico: " . $total_emitidas_historico);
        $total = $total_emitidas_visor_total + $total_emitidas_historico;
        array_push($array, "Total: " . $total);

        $results = DB::table('licencias_giro_visor AS lgv')->select('m.id', 'm.nombre',
            DB::raw("COUNT(*) FILTER (WHERE lgv.tipo_licencia = 'Refrendo') AS total_refrendo"),
            DB::raw("COUNT(*) FILTER (WHERE lgv.tipo_licencia = 'Nueva') AS total_nueva"),
            DB::raw("(COUNT(*) FILTER (WHERE lgv.tipo_licencia = 'Nueva') + COUNT(*) FILTER (WHERE lgv.tipo_licencia = 'Refrendo')) AS total_final"))
            ->join('municipios AS m', 'lgv.municipio_id', '=', 'm.id')
            ->where('lgv.municipio_id', '<>', 1)
            ->whereNull('lgv.deleted_at')
            ->groupBy('m.id', 'm.nombre')
            ->get();

        $results2 = DB::table('historico_licencias_giro AS lgv')
            ->select('m.id', 'm.nombre', DB::raw('COUNT(*)'))
            ->join('municipios AS m', 'lgv.id_municipio', '=', 'm.id')
            ->where('lgv.id_municipio', '<>', 1)
            ->where('lgv.tipo_licencia', 'Refrendo')
            ->whereNull('lgv.deleted_at')
            ->groupBy('m.id', 'm.nombre')
            ->get();

        $municipios = DB::table('municipios')->select('id', 'nombre')->where('id', '!=', '1')->get()->toarray();
        foreach ($municipios as $municipio) {
            $total_emitidas_visor_municipio = DB::table('licencias_giro_visor')->where('municipio_id', $municipio->id)->get()->count();
            $total_emitidas_historico_municipio = DB::table('historico_licencias_giro')->where('id_municipio', $municipio->id)->where('tipo_licencia', 'Refrendo')->get()->count();
            $total = $total_emitidas_visor_municipio + $total_emitidas_historico_municipio;
            if ($total > 0) {
                array_push($array, $municipio->nombre . ": " . $total);
            }
        }
        return view('reporte', ['results' => $results, 'results2' => $results2, 'total_emitidas_visor_total' => $total_emitidas_visor_total, 'total_emitidas_visor_refrendo' => $total_emitidas_visor_refrendo, 'total_emitidas_visor_nuevas' => $total_emitidas_visor_nuevas, 'total_emitidas_historico' => $total_emitidas_historico, 'total' => $total]);

    }

    public function pieBarMunicipiosAll3(Request $request)
    {
        $currentUser = Auth::user();
        // if($currentUser->roles[0]->id != 5) return $this->errorResponse('Sin permisos',403);
        $now = Carbon::now();
        $nowMore1 = Carbon::now()->addYear();
        $def1 = '01/01/' . $now->year;
        $def2 = '01/01/' . $nowMore1->year;
        $d = $request->all();
        // return $d;
        $def1 = $d['f_inicio'];
        $def2 = $d['f_final'];
        $dataMunicipio = DB::table('licencias_giro_visor')
            ->join('consulta_requisitos', 'consulta_requisitos.folio', '=', 'licencias_giro_visor.folio')
            ->whereBetween('licencias_giro_visor.created_at', [$def1, $def2])
            ->selectRaw('consulta_requisitos.id_municipio as extra,consulta_requisitos.municipio as name,count(licencias_giro_visor.id) as value')
            ->groupBy('id_municipio', 'municipio')
            ->get();
        // if($dataMunicipio)

        return $this->successResponse($dataMunicipio);
        // $this->errorResponse('Sin data',400);
    }
    /*
    public function pieBarMunicipiosAll(Request $request) {
    $currentUser = Auth::user();
    // if($currentUser->roles[0]->id != 5) return $this->errorResponse('Sin permisos',403);
    $d = $request->all();
    $def1 = $d['f_inicio'] ?? Carbon::now()->format('Y-m-d');
    $def2 = $d['f_final'] ?? Carbon::now()->addYear()->format('Y-m-d');
    $tipoLicencia = $d['tipo_licencia'] ?? 'Todas';
    $query1 = DB::table('licencias_giro_visor as lg')
    ->leftJoin('consulta_requisitos as cr', 'lg.folio', '=', 'cr.folio')
    ->select('cr.id_municipio as extra', 'cr.municipio as name', DB::raw('count(lg.id) as value'))
    ->where('lg.municipio_id', '<>', 1)
    ->whereNull('lg.deleted_at')
    ->whereBetween('lg.created_at', [$def1, $def2]);
    if ($tipoLicencia === 'Refrendo') {
    $query1->where('lg.tipo_licencia', 'Refrendo');
    } elseif ($tipoLicencia === 'Nueva') {
    $query1->where('lg.tipo_licencia', 'Nueva');
    }
    $query1 = $query1->groupBy('cr.id_municipio', 'cr.municipio');
    if ($tipoLicencia === 'Refrendo' || $tipoLicencia === 'Todas') {
    $query2 = DB::table('historico_licencias_giro')
    ->selectRaw('
    id_municipio as extra,
    (SELECT nombre FROM municipios WHERE id = historico_licencias_giro.id_municipio) AS name,
    count(id) as value')
    ->where('tipo_licencia', 'Refrendo')
    ->whereNull('deleted_at')
    ->where('id_municipio', '<>', 1)
    ->whereBetween('created_at', [$def1, $def2])
    ->groupBy('id_municipio');

    $dataMunicipio = $query1->unionAll($query2)->get();
    } else { // Only use $query1 if the license type is "Nueva"
    $dataMunicipio = $query1->get();
    }
    return $dataMunicipio->isEmpty() ? $this->errorResponse('Sin data', 400) : $this->successResponse($dataMunicipio);
    }*/

    public function pieBarMunicipiosAll(Request $request)
    {
        $currentUser = Auth::user();
        // if($currentUser->roles[0]->id != 5) return $this->errorResponse('Sin permisos',403);
        $d = $request->all();
        $def1 = $d['f_inicio'] ?? Carbon::now()->format('Y-m-d');
        $def2 = $d['f_final'] ?? Carbon::now()->addYear()->format('Y-m-d');
        $tipoLicencia = $d['tipo_licencia'] ?? 'Todas';
        $origenLicencia = $d['origen_licencia'] ?? 'Todas';
        $dataMunicipio = collect();

        if ($origenLicencia === 'VU' || $origenLicencia === 'Todas') {
            $query1 = DB::table('licencias_giro_visor as lg')
                ->leftJoin('consulta_requisitos as cr', 'lg.folio', '=', 'cr.folio')
                ->select('cr.id_municipio as extra', 'cr.municipio as name', DB::raw('count(lg.id) as value'))
                ->where('lg.municipio_id', '<>', 1)
                ->whereNull('lg.deleted_at')
                ->whereBetween('lg.created_at', [$def1, $def2]);

            if ($tipoLicencia !== 'Todas') {
                $query1->where('lg.tipo_licencia', $tipoLicencia);
            }

            $dataMunicipio = $query1->groupBy('cr.id_municipio', 'cr.municipio')->get();
        }

        if ($origenLicencia === 'Refrendo_e' || $origenLicencia === 'Todas') {
            $query2 = DB::table('historico_licencias_giro')
                ->selectRaw('id_municipio as extra, (SELECT nombre FROM municipios WHERE id = historico_licencias_giro.id_municipio) AS name, count(id) as value')
                ->whereNull('deleted_at')
                ->where('id_municipio', '<>', 1)
                ->whereBetween('created_at', [$def1, $def2])
                ->groupBy('id_municipio');

            if ($tipoLicencia !== 'Todas') {
                $query2->where('tipo_licencia', $tipoLicencia);
            }

            if ($origenLicencia === 'Todas') {
                $dataMunicipio = $dataMunicipio->concat($query2->get());
            } else {
                $dataMunicipio = $query2->get();
            }
        }

        return $dataMunicipio->isEmpty() ? $this->errorResponse('Sin data', 400) : $this->successResponse($dataMunicipio);
    }


    public function pieBarMunicipiosById(Request $request, $id)
    {
        $currentUser = Auth::user();
        $d = $request->all();
        $def1 = $d['f_inicio'] ?? Carbon::now()->format('Y-m-d');
        $def2 = $d['f_final'] ?? Carbon::now()->endOfYear()->format('Y-m-d'); // Ensures end of the current year
        $tipoLicencia = $d['tipo_licencia'] ?? 'Todas';
        $origenLicencia = $d['origen_licencia'] ?? 'Todas';
        $dataMunicipio = collect();

        // Handling for 'VU' or all types
        if ($origenLicencia === 'VU' || $origenLicencia === 'Todas') {
            $query1 = DB::table('licencias_giro_visor as lg')
                ->leftJoin('consulta_requisitos as cr', 'lg.folio', '=', 'cr.folio')
                ->select('*')
                ->where('lg.municipio_id', $id)
                ->whereNull('lg.deleted_at')
                ->whereBetween('lg.created_at', [$def1, $def2]);

            if ($tipoLicencia !== 'Todas') {
                $query1->where('lg.tipo_licencia', $tipoLicencia);
            }

            $dataMunicipio = $query1->get();
        }

        // Handling for 'Refrendo_e' or all types
        if ($origenLicencia === 'Refrendo_e' || $origenLicencia === 'Todas') {
            $query2 = DB::table('historico_licencias_giro')
                ->selectRaw('*')
                ->whereNull('deleted_at')
                ->where('id_municipio', $id)
                ->whereBetween('created_at', [$def1, $def2]);

            if ($tipoLicencia !== 'Todas') {
                $query2->where('tipo_licencia', $tipoLicencia);
            }

            if ($origenLicencia === 'Todas') {
                $dataMunicipio = $dataMunicipio->concat($query2->get());
            } else {
                $dataMunicipio = $query2->get();
            }
        }

        return response()->json($dataMunicipio);
    }



    public function barraList(Request $request)
    {
        $currentUser = Auth::user();
        // Uncomment the next line if permission check is needed.
        // if ($currentUser->roles[0]->id != 5) return $this->errorResponse('Sin permisos',403);

        $d = $request->all();

        $def1 = $d['fecha_inicio'];
        $def2 = $d['fecha_fin'];
        $tipoLicencia = $d['tipo_licencia'] ?? 'Todas';
        $origenLicencia = $d['origen_licencia'] ?? 'Todas';
        $id_municipio = $currentUser->roles[0]->id != 5 ? $currentUser->id_municipio : $d['municipio'];

        // Query for current data
        $query = DB::table('licencias_giro_visor as lg')
                   ->join('consulta_requisitos as cr', 'cr.folio', '=', 'lg.folio')
                   ->select('cr.id_municipio as extra', 'cr.municipio as name', DB::raw('count(lg.id) as value'), 'lg.numero_lic', 'lg.dueno', 'lg.anio_licencia')
                   ->where('cr.id_municipio', $id_municipio)
                   ->whereBetween('lg.created_at', [$def1, $def2]);

        if ($tipoLicencia !== 'Todas') {
            $query->where('lg.tipo_licencia', $tipoLicencia);
        }

        if (!empty($d['mes'])) {
            $query->whereRaw('EXTRACT(MONTH FROM lg.created_at) = ?', [$d['mes']]);
        }

        $data = $query->groupBy('cr.id_municipio', 'cr.municipio')->get();

        // Query for historical data
        if ($origenLicencia === 'Refrendo_e' || $origenLicencia === 'Todas') {
            $query2 = DB::table('historico_licencias_giro')
                        ->selectRaw('id_municipio as extra, (SELECT nombre FROM municipios WHERE id = id_municipio) AS name, count(id) as value')
                        ->whereNull('deleted_at')
                        ->where('id_municipio', '<>', 1)
                        ->whereBetween('created_at', [$def1, $def2])
                        ->groupBy('id_municipio');

            if ($tipoLicencia !== 'Todas') {
                $query2->where('tipo_licencia', $tipoLicencia);
            }

            $historicalData = $query2->get();
            $data = $data->concat($historicalData);
        }

        return $data->isEmpty() ? $this->errorResponse('Sin data', 400) : $this->successResponse($data);
    }

    public function lineTimeLicAdmin(Request $request)
    {
        $currentUser = Auth::user();
        $d = $request->all();
        $id_municipio = $d['id_municipio'] ?? null;

        $consulta = DB::table('consulta_requisitos')
            ->leftJoin('tramite', 'consulta_requisitos.folio', '=', 'tramite.folio')
            ->whereNull('tramite.id')

            ->where('consulta_requisitos.id_municipio', '<>', 1)
            ->Where(function ($query) use ($id_municipio) {
                if ($id_municipio) {
                    $query->where('consulta_requisitos.id_municipio', $id_municipio);
                }
            })->count();
        $inicio = DB::table('consulta_requisitos')
            ->leftJoin('tramite', 'consulta_requisitos.folio', '=', 'tramite.folio')
            ->whereNull('tramite.enviado_revisores')
            ->where('consulta_requisitos.id_municipio', '<>', 1)
            ->Where(function ($query) use ($id_municipio) {
                if ($id_municipio) {
                    $query->where('consulta_requisitos.id_municipio', $id_municipio);
                }
            })->count();

        $revision = DB::table('consulta_requisitos')
            ->leftJoin('tramite', 'consulta_requisitos.folio', '=', 'tramite.folio')
            ->where('tramite.enviado_revisores', 1)
            ->where('tramite.pdf_licencia', '=', '')

            ->where('consulta_requisitos.id_municipio', '<>', 1)
            ->Where(function ($query) use ($id_municipio) {
                if ($id_municipio) {
                    $query->where('consulta_requisitos.id_municipio', $id_municipio);
                }
            })->count();

        $emitidas = DB::table('consulta_requisitos')
            ->join('licencias_giro_visor', 'consulta_requisitos.folio', '=', 'licencias_giro_visor.folio')
            ->whereNotNull('licencias_giro_visor.id')
            ->Where(function ($query) use ($id_municipio) {
                if ($id_municipio) {
                    $query->where('consulta_requisitos.id_municipio', $id_municipio);
                }
            })->count();

        $query1 = DB::table('licencias_giro_visor as lg')
            ->leftJoin('consulta_requisitos as cr', 'lg.folio', '=', 'cr.folio')
            ->select(DB::raw('count(lg.id) as value'))
            ->where('lg.municipio_id', '<>', 1)
            ->whereNull('lg.deleted_at');

        // Segunda consulta
        $query2 = DB::table('historico_licencias_giro')
            ->select(DB::raw('count(id) as value'))
            ->where('tipo_licencia', 'Refrendo')
            ->whereNull('deleted_at')
            ->where('id_municipio', '<>', 1);

        // Unir las consultas y sumar el valor total
        $total = $query1->unionAll($query2)
            ->get()
            ->sum('value');

        $data = array('consulta' => $consulta, 'inicio' => $inicio, 'revision' => $revision, 'emitidas' => $total);
        return $this->successResponse($data);

    }

    public function reporteCompletoFichas()
    {
        $reporte_consultas_ficha_tecnicas = DB::table('reporte_consultas_ficha_tecnicas')
            ->select('*')
            ->orderBy('id', 'asc')
            ->get();

        $formatted_report = [];
        foreach ($reporte_consultas_ficha_tecnicas as $reporte) {

            $formatted_report[] = [
                'ID' => $reporte->id,
                'Nombre ' => $reporte->nombre,
                'Email' => $reporte->correo,
                'Edad' => $reporte->edad,
                'Ciudad' => $reporte->ciudad,
                'Sector' => $reporte->sector,
                'Usos' => $reporte->usos,
                'Direccion' => $reporte->direccion,
                'Municipio' => $reporte->municipio,
                'Created_At' => date('Y-m-d H:i:s', strtotime($reporte->created_at)),

            ];
        }

        $sectors_count = $this->getSectorsCount($formatted_report);
        $uses_count = $this->getUsesCount($formatted_report);
        $total_records = count($formatted_report);

        $sectors_percentage = $this->calculatePercentages($sectors_count, $total_records);
        $uses_percentage = $this->calculatePercentages($uses_count, $total_records);
        $age_distribution = $this->getAgeDistribution($formatted_report);
        $top_cities = $this->getTopCities($formatted_report);
        $users_per_municipality = $this->getUsersPerMunicipality($formatted_report);

        return $this->successResponse([
            'sectors_percentage' => $sectors_percentage,
            'uses_percentage' => $uses_percentage,
            'age_distribution' => $age_distribution,
            'top_cities' => $top_cities,
            'users_per_municipality' => $users_per_municipality,
            'data' => $formatted_report,

        ]);

        // return $this->successResponse($formatted_report);
    }

    private function getSectorsCount($data)
    {
        $sectors_count = [];
        foreach ($data as $row) {
            if (!isset($sectors_count[$row['Sector']])) {
                $sectors_count[$row['Sector']] = 0;
            }
            $sectors_count[$row['Sector']]++;
        }
        return $sectors_count;
    }

    private function getUsesCount($data)
    {
        $uses_count = [];
        foreach ($data as $row) {
            $uses = unserialize($row['Usos']);
            foreach ($uses as $use) {
                if (!isset($uses_count[$use])) {
                    $uses_count[$use] = 0;
                }
                $uses_count[$use]++;
            }
        }
        return $uses_count;
    }

    private function calculatePercentages($counts, $total)
    {
        $percentages = [];
        foreach ($counts as $key => $count) {
            $percentages[$key] = round(($count / $total) * 100, 2);
        }
        return $percentages;
    }

    private function getAgeDistribution($data)
    {
        $age_groups = [
            '18-24' => 0,
            '25-34' => 0,
            '35-44' => 0,
            '44-55' => 0,
            '55-64' => 0,

        ];

        foreach ($data as $row) {
            $age = (int) $row['Edad'];
            if ($age >= 18 && $age <= 24) {
                $age_groups['18-24']++;
            } elseif ($age >= 25 && $age <= 34) {
                $age_groups['25-34']++;
            } elseif ($age >= 35 && $age <= 44) {
                $age_groups['35-44']++;
            }
            // ... (add more conditions for other age groups)
        }

        return $age_groups;
    }

    private function getTopCities($data)
    {
        $cities_count = [];
        foreach ($data as $row) {
            if (!isset($cities_count[$row['Ciudad']])) {
                $cities_count[$row['Ciudad']] = 0;
            }
            $cities_count[$row['Ciudad']]++;
        }
        arsort($cities_count);
        return $cities_count;
    }

    private function getUsersPerMunicipality($data)
    {
        $municipalities_count = [];
        foreach ($data as $row) {
            $municipality = $row['Municipio'];
            if (!isset($municipalities_count[$municipality])) {
                $municipalities_count[$municipality] = 0;
            }
            $municipalities_count[$municipality]++;
        }
        return $municipalities_count;
    }

    public function obtenerRefrendos()
    {
        // Subconsulta para 'fechas'
        $fechas = DB::table('historico_licencias_giro')
            ->select('id_municipio', DB::raw('MIN(created_at) AS fecha_inicio'))
            ->where('tipo_licencia', 'Refrendo')
            ->where('id_municipio', '<>', 1)
            ->whereNull('deleted_at')
            ->groupBy('id_municipio');

        // Consulta principal para 'semanas'
        $resultados = DB::table('historico_licencias_giro as h')
            ->joinSub($fechas, 'f', function ($join) {
                $join->on('h.id_municipio', '=', 'f.id_municipio');
            })
            ->join('municipios as m', 'h.id_municipio', '=', 'm.id')
            ->select(
                'm.nombre AS nombre_municipio',
                DB::raw("TO_CHAR(DATE_TRUNC('week', h.created_at), 'DD/MM/YYYY') AS inicio_semana"),
                DB::raw("TO_CHAR(DATE_TRUNC('week', h.created_at) + INTERVAL '6 days', 'DD/MM/YYYY') AS fin_semana"),
                DB::raw('COUNT(*) AS total_licencias')

            )
            ->where('h.created_at', '>=', DB::raw('f.fecha_inicio'))
            ->where('h.tipo_licencia', 'Refrendo')
            ->where('h.id_municipio', '<>', 1)
            ->whereNull('h.deleted_at')
            ->groupBy('h.id_municipio', DB::raw('DATE_TRUNC(\'week\', h.created_at)'), 'm.nombre')
            ->orderBy('h.id_municipio')
            ->orderBy(DB::raw('DATE_TRUNC(\'week\', h.created_at)'))
            ->get();

        return $resultados;
    }
    public function obtenerLicenciasVisor()
    {
        // Subconsulta para 'fechas'
        $fechas = DB::table('licencias_giro_visor')
            ->select('municipio_id', DB::raw('MIN(created_at) AS fecha_inicio'))
            ->where('municipio_id', '<>', 1)
            ->groupBy('municipio_id');
        // Consulta principal para 'semanas'
        $resultados = DB::table('licencias_giro_visor as l')
            ->joinSub($fechas, 'f', function ($join) {
                $join->on('l.municipio_id', '=', 'f.municipio_id');
            })
            ->join('municipios as m', 'l.municipio_id', '=', 'm.id')
            ->select(
                'm.nombre AS nombre_municipio',
                DB::raw("TO_CHAR(DATE_TRUNC('week', l.created_at), 'DD/MM/YYYY') AS inicio_semana"),
                DB::raw("TO_CHAR(DATE_TRUNC('week', l.created_at) + INTERVAL '6 days', 'DD/MM/YYYY') AS fin_semana"),
                DB::raw('COUNT(*) AS total_licencias')
            )
            ->where('l.created_at', '>=', DB::raw('f.fecha_inicio'))
            ->where('l.municipio_id', '<>', 1)
            ->groupBy('l.municipio_id', DB::raw('DATE_TRUNC(\'week\', l.created_at)'), 'm.nombre')
            ->orderBy('l.municipio_id')
            ->orderBy(DB::raw('DATE_TRUNC(\'week\', l.created_at)'))
            ->get();

        return $resultados;
    }

    public function obtenerDatosCombinados()
    {
        // Obtener datos de refrendos
        $refrendos = $this->obtenerRefrendos(); // Assuming obtenerRefrendos returns a Collection
        // Obtener datos de licencias visor
        $licenciasVisor = $this->obtenerLicenciasVisor(); // Assuming obtenerLicenciasVisor returns a Collection
        // Combine the collections
        $combinedData = $refrendos->merge($licenciasVisor);
        // Sort by 'inicio_semana' (you might need to adjust this depending on actual data format)
        $sortedData = $combinedData->sortBy(function ($item) {
            return strtotime($item->inicio_semana);
        });
        return $sortedData;
    }

    public function exportToExcel()
    {
        $data = $this->obtenerDatosCombinados();
        return Excel::download(new DataExport($data), 'data.xlsx');
    }

}
