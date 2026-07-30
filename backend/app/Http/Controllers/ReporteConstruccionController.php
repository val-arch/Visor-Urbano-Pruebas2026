<?php
namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\TramiteConstruccion;
use App\Models\NotificacionUser;
use App\Models\FirmaMunicipioConstruccion;
use App\Models\MunicipioConstruccion;
use App\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;



class ReporteConstruccionController extends Controller{
    use ApiResponser;

    public function lineTimeLic(){
        $currentUser = Auth::user();

        $id_municipio = $currentUser->id_municipio;

        $consulta = DB::table('consulta_requisitos_construccion')
            ->leftJoin('tramite_construccion','consulta_requisitos_construccion.folio','=','tramite_construccion.folio')
            ->whereNull('tramite_construccion.id')
            ->where('consulta_requisitos_construccion.id_municipio',$id_municipio)->count();
        $inicio = DB::table('consulta_requisitos_construccion')
        ->leftJoin('tramite_construccion','consulta_requisitos_construccion.folio','=','tramite_construccion.folio')
        ->whereNull('tramite_construccion.enviado_revisores')
        ->where('consulta_requisitos_construccion.id_municipio',$id_municipio)->count();

        $revision = DB::table('consulta_requisitos_construccion')
        ->leftJoin('tramite_construccion','consulta_requisitos_construccion.folio','=','tramite_construccion.folio')
        ->where('tramite_construccion.enviado_revisores',1)
        ->where('tramite_construccion.pdf_licencia','=','')
        ->where('consulta_requisitos_construccion.id_municipio',$id_municipio)->count();

        $emitidas = DB::table('consulta_requisitos_construccion')
        ->join('licencias_construccion_visor','consulta_requisitos_construccion.folio','=','licencias_construccion_visor.folio')
        ->whereNotNull('licencias_construccion_visor.id')
        ->where('consulta_requisitos_construccion.id_municipio',$id_municipio)->count();

        $data = array('consulta'=>$consulta,'inicio'=>$inicio,'revision'=>$revision,'emitidas'=>$emitidas);
        return $this->successResponse($data);
        
    }

    public function barraAnual(){
        $currentUser = Auth::user();

        $id_municipio = $currentUser->id_municipio;
        $formato=[];
        $year = Carbon::now()->year;
        $mes = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        $data = DB::select('SELECT count(tra.*), EXTRACT(Month FROM tra.created_at) as mes FROM licencias_construccion_visor tra 
            join consulta_requisitos_construccion cons on tra.folio = cons.folio 
            where cons.id_municipio = :municipio and EXTRACT(YEAR FROM tra.created_at) = :yeart group by mes',
            ['municipio'=>$id_municipio,'yeart'=>$year]);

        foreach ($data as $key) {
            array_push($formato,array('name'=>$mes[$key->mes-1],'value'=>$key->count,'extra'=>$key->mes));
            // array_push($formato,array('name'=>$mes[$key->mes-1],'value'=>$key->count));
        }
        return $this->successResponse($formato);
    }

    public function pieRev(){
        $currentUser = Auth::user();
        $id_municipio = $currentUser->id_municipio;

        $aprobados = DB::table('revisiones_dependencias_construccion')
                        ->where('status_actual',1)
                        ->where('rol',4)
                        ->where('id_municipio',$id_municipio)->count();

        $revision = DB::table('revisiones_dependencias_construccion')
                        ->whereNull('status_actual')
                        ->whereIn('rol',[3,4])
                        ->where('id_municipio',$id_municipio)->count();

        $solventaciones = DB::table('revisiones_dependencias_construccion')
                        ->where('status_actual',3)
                        ->where('id_municipio',$id_municipio)->count();

        $desechadas = DB::table('revisiones_dependencias_construccion')
                        ->where('status_actual',2)
                        ->where('rol',4)
                        ->where('id_municipio',$id_municipio)->count();
        
        return $this->successResponse(array('aprobadas'=>$aprobados,'revision'=>$revision,'solventaciones'=>$solventaciones,'desechadas'=>$desechadas));
    }

    public function pieBarMunicipiosAll(Request $request){
        $currentUser = Auth::user();
        // if($currentUser->roles_construccion[0]->id != 5) return $this->errorResponse('Sin permisos',403);
        $now = Carbon::now();
        $nowMore1 = Carbon::now()->addYear();
        $def1 = '01/01/'.$now->year;
        $def2 = '01/01/'.$nowMore1->year;
        $d = $request->all();
        // return $d;
        $def1 = $d['f_inicio'];
        $def2 = $d['f_final'];
        $dataMunicipio = DB::table('licencias_construccion_visor')
                        ->join('consulta_requisitos_construccion','consulta_requisitos_construccion.folio','=','licencias_construccion_visor.folio')
                        ->whereBetween('licencias_construccion_visor.created_at',[$def1,$def2])
                        ->selectRaw('consulta_requisitos_construccion.id_municipio as extra,consulta_requisitos_construccion.municipio as name,count(licencias_construccion_visor.id) as value')
                        ->groupBy('id_municipio','municipio')
                        ->get();
        // if($dataMunicipio) 
        return $this->successResponse($dataMunicipio);
        // $this->errorResponse('Sin data',400);
    }
    public function pieBarMunicipiosById(Request $request, $id){
        $currentUser = Auth::user();
        // if($currentUser->roles_construccion[0]->id != 5) return $this->errorResponse('Sin permisos',403);
        $now = Carbon::now();
        $d = $request->all();
        $nowMore1 = Carbon::now()->addYear();
        $def1 = '01/01/'.$now->year;
        $def2 = '01/01/'.$nowMore1->year;
        $def1 = $d['f_inicio'];
        $def2 = $d['f_final'];
        $dataMunicipio=[];
        $data = DB::table('licencias_construccion_visor')
                        ->join('consulta_requisitos_construccion','consulta_requisitos_construccion.folio','=','licencias_construccion_visor.folio')
                        ->where('consulta_requisitos_construccion.id_municipio',$id)
                        ->whereBetween('licencias_construccion_visor.created_at',[$def1,$def2])
                        ->selectRaw('count(licencias_construccion_visor.id) as value, EXTRACT(Month FROM licencias_construccion_visor.created_at) as mes')
                        ->groupBy('mes')
                        ->get();

            $mes = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
            foreach ($data as $key) {
                array_push($dataMunicipio,array('name'=>$mes[$key->mes-1],'value'=>$key->value,'extra'=>$key->mes));
            }
            return $this->successResponse($dataMunicipio);
    }


    public function barraList(Request $request){
        $d = $request->all();
        $currentUser = Auth::user();
        if($currentUser->roles_construccion[0]->id != 5){
            $id_municipio = $currentUser->id_municipio;
        }else{
            $id_municipio = $d['municipio'];
        }
        $def1 = $d['fecha_inicio'];
        $def2 = $d['fecha_fin'];
        $data = DB::table('licencias_construccion_visor')
        ->join('consulta_requisitos_construccion','consulta_requisitos_construccion.folio','=','licencias_construccion_visor.folio')
        ->where('consulta_requisitos_construccion.id_municipio',$id_municipio)
        ->whereRaw('EXTRACT(Month FROM licencias_construccion_visor.created_at) = ?',$d['mes'])
        ->whereBetween('licencias_construccion_visor.created_at',[$def1,$def2])
        ->selectRaw('licencias_construccion_visor.*')->get();

        return $this->successResponse($data);
    }

    public function lineTimeLicAdmin(Request $request){
        $currentUser = Auth::user();
        $d = $request->all();
        $id_municipio = $d['id_municipio'] ?? null;

        $consulta = DB::table('consulta_requisitos_construccion')
            ->leftJoin('tramite_construccion','consulta_requisitos_construccion.folio','=','tramite_construccion.folio')
            ->whereNull('tramite_construccion.id')
            ->Where(function($query) use ($id_municipio) {
                if($id_municipio){
                    $query->where('consulta_requisitos_construccion.id_municipio',$id_municipio);
                }
            })->count();
        $inicio = DB::table('consulta_requisitos_construccion')
        ->leftJoin('tramite_construccion','consulta_requisitos_construccion.folio','=','tramite_construccion.folio')
        ->whereNull('tramite_construccion.enviado_revisores')
        ->Where(function($query) use ($id_municipio) {
            if($id_municipio){
                $query->where('consulta_requisitos_construccion.id_municipio',$id_municipio);
            }
        })->count();

        $revision = DB::table('consulta_requisitos_construccion')
        ->leftJoin('tramite_construccion','consulta_requisitos_construccion.folio','=','tramite_construccion.folio')
        ->where('tramite_construccion.enviado_revisores',1)
        ->where('tramite_construccion.pdf_licencia','=','')
        ->Where(function($query) use ($id_municipio) {
            if($id_municipio){
                $query->where('consulta_requisitos_construccion.id_municipio',$id_municipio);
            }
        })->count();

        $emitidas = DB::table('consulta_requisitos_construccion')
        ->join('licencias_construccion_visor','consulta_requisitos_construccion.folio','=','licencias_construccion_visor.folio')
        ->whereNotNull('licencias_construccion_visor.id')
        ->Where(function($query) use ($id_municipio) {
            if($id_municipio){
                $query->where('consulta_requisitos_construccion.id_municipio',$id_municipio);
            }
        })->count();

        $data = array('consulta'=>$consulta,'inicio'=>$inicio,'revision'=>$revision,'emitidas'=>$emitidas);
        return $this->successResponse($data);
        
    }


    

}