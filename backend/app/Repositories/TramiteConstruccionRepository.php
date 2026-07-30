<?php

namespace App\Repositories;

use App\DTOs\TramitesConstriccion\TramiteCreateDto;
use App\DTOs\TramitesConstriccion\TramiteUpdateDto;
use App\Models\AperturaProvisionalConstruccion;
use App\Models\CampoConstruccion;
use App\Models\ConsultaRequisitoConstruccion;
use App\Models\MunicipioConstruccion;
use App\Models\Product;
use App\Models\ResolucionConstruccion;
use App\Models\RespuestaConstruccion;
use App\Models\RevisionesConstruccion;
use App\Models\TramiteConstruccion;
use App\Models\Usuario;
use App\Models\TipoTramiteConstruccion;
use App\Repositories\Interfaces\ITramiteConstruccionRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\InteresadosConstruccion;
use App\Mail\notificacionRolesConstruccion;
use App\Mail\ingresoSinFirmaConstruccionMailDependencia;
use App\Mail\ingresoSinFirmaConstruccionMailCiudadano;

class TramiteConstruccionRepository implements ITramiteConstruccionRepository
{
    public function paginate(int $take): Paginator
    {
        return Product::orderBy('name')
        //   ->where('price', '>=', 80)
        //   ->where('price', '<=', 100)
            ->whereBetween('price', [80, 100])
            ->simplePaginate($take);
    }

    public function find(int $id):  ? Product
    {
        return Product::find($id);
    }

    public function file(string $folio, string $inputName, UploadedFile $file)
    {
        $user = Auth::user();
        $entry = DB::table('licencias_construccion_visor')->where('folio', base64_decode($folio))->first();

        if ($entry) {
            $folioAux = str_replace("/", "-", base64_decode($folio));
            $filename = $folioAux . '_' . $inputName . '.' . $file->getClientOriginalExtension();
            // path
            $path = public_path();
            // upload
            $file->move($path . '/ProrrogaFiles/' . $user->id_municipio . '/', $filename);

            return '/ProrrogaFiles/' . $user->id_municipio . '/' . $filename;

        }

    }

    public function ingreso(string $folio)
    {

        $tramiteData = TramiteConstruccion::where('folio', $folio)->firstOrFail();
        $tramiteData->folio;
        $mytime = Carbon::now();
        $mytime->toDateTimeString();
        $user = Auth::user();
        $role_id = Auth::user()->roles_construccion[0]->id;
        $step_actual = $tramiteData->step_actual;
        $year = Carbon::now()->year;

        if ($step_actual == 0) {
            if ($role_id == 2) {
                $entry = TramiteConstruccion::where('folio', $folio);
                $entry->step_actual = 1;
                $entry->fecha_visto_ventanilla = $mytime;
                $entry->rol_ingreso = $role_id;
                $entry->super_id_rol = 1;
            } else {
                $entry = TramiteConstruccion::where('folio', $folio);
                $entry->step_actual = 1;
                $entry->fecha_inicio_tramite = $mytime;
                $entry->rol_ingreso = $role_id;
                $entry->super_id_rol = 1;
            }
        }

        $consulta_requisitos = ConsultaRequisitoConstruccion::where('folio', $folio)->get();

        $count = ConsultaRequisitoConstruccion::where('id_municipio', $consulta_requisitos[0]->id_municipio)->where('folio_interno', '!=', null)->count();

        if($tramiteData->step_actual == 0){
            $folio_interno = ($count+1) ."/" . $year;
            ConsultaRequisitoConstruccion::where('folio', $folio)->where('folio_interno', null)->update(['folio_interno' =>  $folio_interno]);
        }

        return $tramiteData;
    }

    public function noFirmaElectronica(string $folio)
    {
        $tramiteData = TramiteConstruccion::where('folio', $folio)->first();
        $user = Auth::user();
        $role_id = Auth::user()->roles_construccion[0]->id;
        if ($user->id == $tramiteData->id_usuario) {
            $t = TramiteConstruccion::find($tramiteData->id);
            $t->tengo_firma = 0;
            $t->fecha_no_firma = Carbon::now();
            $t->save();
            $data = ['acuse_no_firma' => 'acuseNoFirmadoConstruccion/' . base64_encode($folio)];

            $consulta = ConsultaRequisitoConstruccion::where('folio', $folio)->get();
            $nombre_tramite = TipoTramiteConstruccion::where('id', $consulta[0]->tramite_relacionado)->get();
            $nombre_tramite = $nombre_tramite[0]->tramite;
            $municipio = MunicipioConstruccion::where('id', $consulta[0]->id_municipio)->get();
            $municipio_nombre = $municipio[0]->nombre;
            $folio_requisitos = $consulta[0]->folio;
            $correo_int = RespuestaConstruccion::where('id_tramite', $consulta[0]->id)->where('name', 'int_correo')->first();
            $correo_int = $correo_int->value;
            $correo_prop = RespuestaConstruccion::where('id_tramite', $consulta[0]->id)->where('name', 'prop_correo')->first();
            $correo_prop = $correo_prop->value;
            if($correo_int == $correo_prop){
                Mail::to($correo_int)->send(new ingresoSinFirmaConstruccionMailCiudadano($nombre_tramite, $municipio_nombre));
            }else{
                Mail::to($correo_int)->send(new ingresoSinFirmaConstruccionMailCiudadano($nombre_tramite, $municipio_nombre));
                Mail::to($correo_prop)->send(new ingresoSinFirmaConstruccionMailCiudadano($nombre_tramite, $municipio_nombre));
            }
            Mail::to($municipio[0]->correo_dependencia)->send(new ingresoSinFirmaConstruccionMailDependencia($nombre_tramite, $folio_requisitos));
            return $data;
        } else {
            return false;
        }
    }

    public function ingresoTramiteGenerarLicencia(string $folio)
    {
        $tramiteData = TramiteConstruccion::where('folio', $folio)->get();
        $consultaData = ConsultaRequisitoConstruccion::where('folio', $folio)->get();
        $user = Auth::user();
        $role_id = $user->roles_construccion[0]->id;

        if ($role_id > 1) {
            $t = TramiteConstruccion::find($tramiteData[0]->id);
            $t->enviado_revisores = 1;
            $t->fecha_enviado_revisores = Carbon::now();
            $t->licencia_generada_ventanilla = $user->id;
            $t->save();
            $revisores = [3, 4];
            $campos = DB::table('campos_construccion')
                ->join('respuestas_construccion', 'campos_construccion.name', '=', 'respuestas_construccion.name')
                ->where('respuestas_construccion.id_tramite', $consultaData[0]->id)
                ->where('campos_construccion.type', 'file')
                ->whereNotNull('campos_construccion.condicion_dependencia')
                ->select('campos_construccion.*', 'respuestas_construccion.value')
                ->get();
            if (count($campos) > 0) {
                foreach ($campos as $campo) {
                    $d = explode(",", $campo->condicion_dependencia);
                    foreach ($d as $key => $value) {
                        if (array_search($value, $revisores) == false) {
                            $revisores[] = $value;
                        }
                    }
                }
            }

            $insertRevisores = [];
            //status 7 ventanilla, status 8 revisor, status 9 director
            $stats_by_rol = 0;
            switch ($role_id) {
                case 2 : //ventanilla
                    $stats_by_rol = 7;
                    break;
                case 3: //revisor
                    $stats_by_rol = 8;
                    break;
                case 4: //Director
                    $stats_by_rol = 9;
                    break;

                default:
                    $stats_by_rol = 10;
                    break;
            }
            foreach ($revisores as $value) {
                // return $value;
                if ($value) {
                    RevisionesConstruccion::create([
                        'id_tramite' => $consultaData[0]->id,
                        'id_municipio' => $consultaData[0]->id_municipio,
                        'folio' => $folio,
                        'id_usuario' => 0,
                        'rol' => $value,
                        'status_actual' => $stats_by_rol,
                        'fecha_inicio' => Carbon::now(),
                        'fecha_actualizacion' => Carbon::now(),
                        'id_usuario' => $user->id,
                    ]);
                }

            }

            $mun = MunicipioConstruccion::find($consultaData[0]->id_municipio);
            $urlAperturaProv = true;

            if ($urlAperturaProv) {
                $response['data'] = $t;
                $response['message'] = 'No se puede emitir la licencia, clic en continuar';
                $response['status'] = true;
                return $response;
            } else {
                return false;
            }
        }
    }

    public function setRevision($consultaData, $consultaData2, $folio, $value, $resolver, $id_rev)
    {
        RevisionesConstruccion::create([
            'id_tramite' => $consultaData,
            'id_municipio' => $consultaData2,
            'folio' => $folio,
            'id_usuario' => 0,
            'rol' => $value,
            'resolver' => $resolver,
            'id_rev' => $id_rev,
            'fecha_inicio' => Carbon::now(),
            'fecha_actualizacion' => Carbon::now(),
        ]);
    }

    public function ingresoTramiteContinuar(string $folio)
    {

        $tramiteData = TramiteConstruccion::where('folio', $folio)->get();
        if($tramiteData[0]->enviado_revisores == 1){
            return "tramite_duplicado";
        }
        $consultaData = ConsultaRequisitoConstruccion::where('folio', $folio)->get();
        $user = Auth::user();
        $role_id = $user->roles_construccion[0]->id;
        if (($role_id > 1) || ($user->id == $tramiteData[0]->id_usuario &&  $tramiteData[0]->firma_usuario != '')) {

            $t = TramiteConstruccion::find($tramiteData[0]->id);
            $t->enviado_revisores = 1;
            $t->fecha_enviado_revisores = Carbon::now();
            $t->save();

            $user = Auth::user();
            $role_id = Auth::user()->roles_construccion[0]->id;
            $orden = ResolucionConstruccion::where('id_municipio', $user->id_municipio)->where('tipo_tramite', $consultaData[0]->tramite_relacionado)
            ->where('orden', '!=', null)
            ->orderBy('orden', 'asc')->get();

            $id_array_orden = array();

            foreach ($orden as $value2) {
                array_push($id_array_orden, $value2->id_rol);
            }
            array_push($id_array_orden, 4);

            //agregar validacion para no insertar repetidos

            if (count($orden) > 0) {
                foreach ($id_array_orden as $value) {
                    if ($value == $orden[0]->id_rol || $value == 4) {
                        $this->setRevision($consultaData[0]->id, $consultaData[0]->id_municipio, $folio, $value, true, null);
                    } else {
                        $this->setRevision($consultaData[0]->id, $consultaData[0]->id_municipio, $folio, $value, false, null);
                    }
                }
            } else {
                $this->setRevision($consultaData[0]->id, $consultaData[0]->id_municipio, $folio, $id_array_orden[0], true, null);
            }

            if($tramiteData[0]->enviado_revisores == null){
                $this->sendMailConstrucciónTrámite($folio, $consultaData[0]->id, 1);
                $this->sendMailConstrucciónTrámite($folio, $consultaData[0]->id, 2);
            }

            $mun = MunicipioConstruccion::find($consultaData[0]->id_municipio);
            $urlAperturaProv = true;
            $restricciones = $consultaData[0]->restricciones;
            $restricciones = json_decode($restricciones, true);
            $urlAperturaProv = false;

            if ($urlAperturaProv) {
                $apertura = AperturaProvisionalConstruccion::where(['folio' => $folio, 'contador' => 1])->get();
                if (count($apertura) == 0) {
                    $ap = new AperturaProvisionalConstruccion();
                    $ap->folio = $folio;
                    $ap->id_tramite = $consultaData[0]->id;
                    $ap->contador = 1;
                    $ap->id_usuario_otorgo = $user->id;
                    $ap->rol_otorgo = $role_id;
                    $ap->fecha_inicio = Carbon::now();
                    $ap->fecha_limite = Carbon::now()->add(30, 'day');
                    $ap->status = 1;
                    $ap->save();
                }
            }

            $data['apertura_provisional_url'] = $urlAperturaProv == true ? 'aperturaProvisional/' . base64_encode($folio) : false;
            if ($role_id == 1) {
                $data['acuse_firma_electronica_url'] = 'acuseFirmadoConstruccion/' . base64_encode($folio);
            }
            if ($role_id > 1) {
                $data['acuse_Ventanilla'] = 'acuseVentanillaConstruccion/' . base64_encode($folio);
            }
            
            return $data;
        } else {
            return false;
        }
    }

    public function sendMailConstrucciónTrámite(string $folio, $id_tramite, $tipo){

        $consulta = ConsultaRequisitoConstruccion::where('folio', $folio)->get();


        $nombre_tramite = TipoTramiteConstruccion::where('id', $consulta[0]->tramite_relacionado)->get();
        $tipo_tramite = $nombre_tramite[0]->id;
        $nombre_tramite = $nombre_tramite[0]->tramite;

        $municipio = MunicipioConstruccion::where('id', $consulta[0]->id_municipio)->get();
        $municipio = $municipio[0]->nombre;

        if($tipo == 1){
            $rol_orden = ResolucionConstruccion::where('id_municipio', $consulta[0]->id_municipio)->where('tipo_tramite', $tipo_tramite)->where('orden', 1)->get();

            if(count($rol_orden)>0){
                $id_rol = $rol_orden[0]->id_rol;
                $users = Usuario::whereHas('roles_construccion', function ($query) use ($id_rol) {
                    $query->where('role_id', $id_rol);
                })->get();
                foreach($users as $user){
                    Mail::to($user->email)->send(new notificacionRolesConstruccion($nombre_tramite, $folio, $municipio));
                }
            }
        }else if($tipo == 2){
            $correo_int = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'int_correo')->first();
            $correo_int = $correo_int->value;

            $correo_prop = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'prop_correo')->first();
            $correo_prop = $correo_prop->value;

            if($correo_int == $correo_prop){
                Mail::to($correo_int)->send(new InteresadosConstruccion($nombre_tramite, $folio, $municipio));
            }else{
                Mail::to($correo_int)->send(new InteresadosConstruccion($nombre_tramite, $folio, $municipio));
                Mail::to($correo_prop)->send(new InteresadosConstruccion($nombre_tramite, $folio, $municipio));
            }
        }
    }

    public function getListado(string $folio = '')
    {

        $user = Auth::user();
        $role_id = Auth::user()->roles_construccion[0]->id;
        switch ($role_id) {
            case 1:
                $data = DB::table('consulta_requisitos_construccion')
                    ->join('tramite_construccion', 'consulta_requisitos_construccion.folio', '=', 'tramite_construccion.folio')
                    ->select('*')
                    ->orderBy('consulta_requisitos_construccion.id', 'desc')
                    ->where('tramite_construccion.id_usuario', $user->id)
                    ->paginate(20);
                break;
            case 2:
                $data = DB::table('consulta_requisitos_construccion')
                    ->join('tramite_construccion', 'consulta_requisitos_construccion.folio', '=', 'tramite_construccion.folio')
                    ->select('consulta_requisitos_construccion.*', 'tramite_construccion.enviado_revisores')
                    ->where('consulta_requisitos_construccion.id_municipio', $user->id_municipio)
                    ->where('tramite_construccion.tengo_firma', 0)
                    ->orWhere('tramite_construccion.id_usuario', '=', 0)
                    ->where('consulta_requisitos_construccion.id_municipio', $user->id_municipio);
                if ($folio != '') {
                    $data->where(function ($q) use ($folio) {
                        $q->where('consulta_requisitos_construccion.folio', 'ilike', '%' . $folio . '%')
                            ->orWhere('consulta_requisitos_construccion.calle', 'ilike', '%' . $folio . '%')
                            ->orWhere('consulta_requisitos_construccion.nombre_solicitante', 'ilike', '%' . $folio . '%');
                    });
                }
                $data = $data->orderBy('consulta_requisitos_construccion.id', 'desc')
                    ->paginate(20);
                break;
            case 3:
                $data = DB::table('consulta_requisitos_construccion')
                    ->join('tramite_construccion', 'consulta_requisitos_construccion.folio', '=', 'tramite_construccion.folio')
                    ->join('revisiones_dependencias_construccion', 'consulta_requisitos_construccion.folio', '=', 'revisiones_dependencias_construccion.folio')
                    ->select('*', DB::raw('(select count(archivos) from solventacion_construccion where solventacion_construccion.id_tramite=consulta_requisitos_construccion.id) as solventacion_ciudadano'))
                    ->where('revisiones_dependencias_construccion.rol', 3)
                    ->where('revisiones_dependencias_construccion.mostrar', true)
                    ->where('revisiones_dependencias_construccion.id_rev', $user->id)
                    ->where('consulta_requisitos_construccion.id_municipio', $user->id_municipio);
                if ($folio != '') {
                    $data->where(function ($q) use ($folio) {
                        $q->where('consulta_requisitos_construccion.folio', 'ilike', '%' . $folio . '%')
                            ->orWhere('consulta_requisitos_construccion.calle', 'ilike', '%' . $folio . '%')
                            ->orWhere('consulta_requisitos_construccion.nombre_solicitante', 'ilike', '%' . $folio . '%');
                    });
                }
                // $data = $data->se
                $data = $data->orderBy('consulta_requisitos_construccion.id', 'desc')
                    ->paginate(20);
                break;
            case 4:
                $data = DB::table('consulta_requisitos_construccion')
                    ->join('tramite_construccion', 'consulta_requisitos_construccion.folio', '=', 'tramite_construccion.folio')
                    ->join('revisiones_dependencias_construccion', 'consulta_requisitos_construccion.folio', '=', 'revisiones_dependencias_construccion.folio')
                    ->join('tipo_tramites_construccion', 'consulta_requisitos_construccion.tramite_relacionado', '=', 'tipo_tramites_construccion.id')
                    ->select('*', DB::raw('(select count(archivos) from solventacion_construccion where solventacion_construccion.id_tramite=consulta_requisitos_construccion.id) as solventacion_ciudadano'), 'tramite_construccion.id AS tramite_construccion_id', 'consulta_requisitos_construccion.folio_interno AS folio_interno', 'tipo_tramites_construccion.tramite', DB::raw('(select max (revisiones_dependencias_construccion.updated_at) from revisiones_dependencias_construccion where revisiones_dependencias_construccion.folio = consulta_requisitos_construccion.folio) as fecha_ultima_resolucion'))
                    ->where('revisiones_dependencias_construccion.rol', $role_id)
                    ->where('consulta_requisitos_construccion.id_municipio', $user->id_municipio);
                if ($folio != '') {
                    $data->where(function ($q) use ($folio) {
                        $q->where('consulta_requisitos_construccion.folio_interno', 'ilike', '%' . $folio . '%')
                            ->orWhere('consulta_requisitos_construccion.calle', 'ilike', '%' . $folio . '%')
                            ->orWhere('consulta_requisitos_construccion.nombre_solicitante', 'ilike', '%' . $folio . '%');
                    });
                }

                $data = $data->orderBy('consulta_requisitos_construccion.id', 'desc')
                    ->paginate(10);
                break;
            default:
                $data = DB::table('consulta_requisitos_construccion')
                    ->join('tramite_construccion', 'consulta_requisitos_construccion.folio', '=', 'tramite_construccion.folio')
                    ->join('revisiones_dependencias_construccion', 'consulta_requisitos_construccion.folio', '=', 'revisiones_dependencias_construccion.folio')
                    ->join('tipo_tramites_construccion', 'consulta_requisitos_construccion.tramite_relacionado', '=', 'tipo_tramites_construccion.id')
                    ->select('*', DB::raw('(select count(archivos) from solventacion_construccion where solventacion_construccion.id_tramite=consulta_requisitos_construccion.id) as solventacion_ciudadano'), 'tramite_construccion.id AS tramite_construccion_id', 'consulta_requisitos_construccion.folio_interno AS folio_interno', 'tipo_tramites_construccion.tramite', DB::raw('(select max (revisiones_dependencias_construccion.updated_at) from revisiones_dependencias_construccion where revisiones_dependencias_construccion.folio = consulta_requisitos_construccion.folio) as fecha_ultima_resolucion'))
                    ->where('revisiones_dependencias_construccion.rol', $role_id)
                    ->where('consulta_requisitos_construccion.id_municipio', $user->id_municipio);
                if ($folio != '') {
                    $data->where(function ($q) use ($folio) {
                        $q->where('consulta_requisitos_construccion.folio_interno', 'ilike', '%' . $folio . '%')
                            ->orWhere('consulta_requisitos_construccion.calle', 'ilike', '%' . $folio . '%')
                            ->orWhere('consulta_requisitos_construccion.nombre_solicitante', 'ilike', '%' . $folio . '%');
                    });
                }
                // $data = $data->se
                $data = $data->orderBy('consulta_requisitos_construccion.id', 'desc')
                    ->paginate(20);
                break;
        }
        if ($data) {
            return $data;
        } else {
            return false;
        }
    }
    public function getListadoVentanilla(string $folio = '')
    {

        $user = Auth::user();
        $role_id = $user->roles_construccion[0]->id;
        if ($role_id < 2) {
            return false;
        }

       

         $data = DB::table('consulta_requisitos_construccion')
            ->join('tramite_construccion', 'consulta_requisitos_construccion.folio', '=', 'tramite_construccion.folio')
            ->select('consulta_requisitos_construccion.*', 'tramite_construccion.*')
            ->where(function($q) use ($user){
                 $q->where('consulta_requisitos_construccion.id_municipio', $user->id_municipio)
                  ->where('tramite_construccion.tengo_firma', 0)
                  ->orWhere('tramite_construccion.id_usuario','=',0)
                   ->where('consulta_requisitos_construccion.id_municipio', $user->id_municipio);
            })->orwhere(function($q) use ($user){
                 $q->where('consulta_requisitos_construccion.id_municipio', $user->id_municipio)
                 ->where('tramite_construccion.enviado_revisores', null);
            })->orderBy('consulta_requisitos_construccion.id', 'desc')->get();
           
        if ($folio != '') {
            $data->where(function ($q) use ($folio) {
                $q->where('consulta_requisitos_construccion.folio', 'ilike', '%' . $folio . '%')
                    ->orWhere('consulta_requisitos_construccion.calle', 'ilike', '%' . $folio . '%')
                    ->orWhere('consulta_requisitos_construccion.nombre_solicitante', 'ilike', '%' . $folio . '%');
            });
        }

        foreach ($data as $value) {

            $nombre = DB::table('respuestas_construccion')->select('value')->where('name', 'int_nombre')->where('id_tramite', $value->id)->get();
            $apellido_1 = DB::table('respuestas_construccion')->select('value')->where('name', 'int_apellidouno')->where('id_tramite', $value->id)->get();
            $apellido_2 = DB::table('respuestas_construccion')->select('value')->where('name', 'int_apellidodos')->where('id_tramite', $value->id)->get();
            $calle = DB::table('respuestas_construccion')->select('value')->where('name', 'obra_calle')->where('id_tramite', $value->id)->get();
            $colonia = DB::table('respuestas_construccion')->select('value')->where('name', 'obra_colonia')->where('id_tramite', $value->id)->get();
            $fecha_inicio_tramite = DB::table('revisiones_dependencias_construccion')->select('fecha_inicio')->where('id_tramite', $value->id)->first();
            $fecha_inicio_tramite = (array) $fecha_inicio_tramite;
            $fecha_ultima_resolucion = DB::table('revisiones_dependencias_construccion')->select('fecha_actualizacion')->where('id_tramite', $value->id)->first();

            $fecha_ultima_resolucion = (array) $fecha_ultima_resolucion;
            foreach ($fecha_inicio_tramite as $value2) {
                $value->fecha_inicio_tramite = $value2;
            }
            foreach ($fecha_ultima_resolucion as $value3) {
                $value->fecha_ultima_resolucion = $value3;
            }
            //$value->fecha_inicio_tramite = $fecha_inicio_tramite->value;
            $value->calle = $calle[0]->value;
            $value->colonia = $colonia[0]->value;
            $value->nombre_solicitante = $nombre[0]->value . " " . $apellido_1[0]->value . " " . $apellido_2[0]->value;

        }
        // $data = $data->se
        $pagina = new Paginator($data, 20);

        if ($pagina) {
            return $pagina;
        } else {
            return false;
        }
    }

    public function getListadoDirRev($folio)
    {

        $user = Auth::user();
        $role_id = $user->roles_construccion[0]->id;
        if ($role_id != 4) {
            return false;
        }
        $data = DB::table('consulta_requisitos_construccion')
            ->join('tramite_construccion', 'consulta_requisitos_construccion.folio', '=', 'tramite_construccion.folio')
            ->join('tipo_tramites_construccion', 'consulta_requisitos_construccion.tramite_relacionado', '=', 'tipo_tramites_construccion.id')
            ->select('*', DB::raw('(select count(archivos) from solventacion where solventacion.id_tramite=consulta_requisitos_construccion.id) as solventacion_ciudadano'), 'tipo_tramites_construccion.tramite', 'consulta_requisitos_construccion.folio_interno AS folio_interno', DB::raw('(select max (revisiones_dependencias_construccion.updated_at) from revisiones_dependencias_construccion where revisiones_dependencias_construccion.folio = consulta_requisitos_construccion.folio) as fecha_ultima_resolucion'))
            ->where('tramite_construccion.aprobado_director', 0)
            ->where('tramite_construccion.enviado_revisores', 1)
            ->where('consulta_requisitos_construccion.id_municipio', $user->id_municipio);



        if ($folio != '') {
            $data->where(function ($q) use ($folio) {
                $q->where('consulta_requisitos_construccion.folio_interno', 'ilike', '%' . $folio . '%')
                    ->orWhere('consulta_requisitos_construccion.calle', 'ilike', '%' . $folio . '%')
                    ->orWhere('consulta_requisitos_construccion.nombre_solicitante', 'ilike', '%' . $folio . '%');
            });
        }

        $data = $data->orderBy('consulta_requisitos_construccion.id', 'desc')
                    ->paginate(10);

        
        if ($data) {
            return $data;
        } else {
            return false;
        }
    }
    public function getListadoDirSolv(string $folio = '')
    {

        $user = Auth::user();
        $role_id = $user->roles_construccion[0]->id;
        if ($role_id < 2) {
            return false;
        }
        $data = DB::table('consulta_requisitos_construccion')->select('tramite_construccion.*', 'consulta_requisitos_construccion.*', 'notificaciones_construccion.id as noti_id')
            ->join('tramite_construccion', 'consulta_requisitos_construccion.folio', '=', 'tramite_construccion.folio')
            ->join('notificaciones_construccion', 'consulta_requisitos_construccion.folio', '=', 'notificaciones_construccion.folio')
            ->where('notificaciones_construccion.id_solventacion', '!=', 0)
            ->where('consulta_requisitos_construccion.id_municipio', $user->id_municipio);
        if ($folio != '') {
            $data->where(function ($q) use ($folio) {
                $q->where('consulta_requisitos_construccion.folio', 'ilike', '%' . $folio . '%')
                    ->orWhere('consulta_requisitos_construccion.calle', 'ilike', '%' . $folio . '%')
                    ->orWhere('consulta_requisitos_construccion.nombre_solicitante', 'ilike', '%' . $folio . '%');
            });
        }
        // $data = $data->se
        $data = $data->orderBy('notificaciones_construccion.id', 'desc')
            ->paginate(20);

        if ($data) {
            return $data;
        } else {
            return false;
        }
    }

    public function getNombreSolicitante($folio)
    {
        $tramiteData = TramiteConstruccion::where('folio', $folio)->get();
        $consultaData = ConsultaRequisitoConstruccion::where('folio', $folio)->get();
        if (empty($consultaData)) {
            return false;
        }
        $caracter = $consultaData[0]->caracter_solicitante;
        $tipo_persona = $consultaData[0]->tipo_persona;
        $id_tramite = $consultaData[0]->id;
        $nombre_persona = '';
        $apellido_1 = '';
        $apellido_2 = '';
        $email = '';
        switch ($caracter) {
            case 'Carta poder':
                $nombre_persona = $this->getRespuesta($id_tramite, 'nombre_apoderado');
                $apellido_1 = $this->getRespuesta($id_tramite, 'apellido_1_apoderado');
                $apellido_2 = $this->getRespuesta($id_tramite, 'apellido_2_apoderado');
                $email = $this->getRespuesta($id_tramite, 'correo_electronico_apoderado');
                break;
            case 'Propietario':
                $nombre_persona = $this->getRespuesta($id_tramite, ($tipo_persona == 'Moral' ? 'nombre_apoderado_moral' : 'nombre_propietario'));
                $apellido_1 = $this->getRespuesta($id_tramite, ($tipo_persona == 'Moral' ? 'apellido_1_apoderado_moral' : 'apellido_1_propietario'));
                $apellido_2 = $this->getRespuesta($id_tramite, ($tipo_persona == 'Moral' ? 'apellido_2_apoderado_moral' : 'apellido_2_propietario'));
                $email = $this->getRespuesta($id_tramite, ($tipo_persona == 'Moral' ? 'correo_electronico_apoderado_moral' : 'correo_electronico_propietario'));
                break;
            case 'Arrendatario':
                $nombre_persona = $this->getRespuesta($id_tramite, ($tipo_persona == 'Moral' ? 'nombre_apoderado_moral' : 'nombre_arrendatario'));
                $apellido_1 = $this->getRespuesta($id_tramite, ($tipo_persona == 'Moral' ? 'apellido_1_apoderado_moral' : 'apellido_1_arrendatario'));
                $apellido_2 = $this->getRespuesta($id_tramite, ($tipo_persona == 'Moral' ? 'apellido_2_apoderado_moral' : 'apellido_2_arrendatario'));
                $email = $this->getRespuesta($id_tramite, ($tipo_persona == 'Moral' ? 'correo_electronico_apoderado_moral' : 'correo_electronico_arrendatario'));
                break;
        }
        //  die();
        $nombre_formado = ($nombre_persona == false ? ' ' : $nombre_persona) . ' ' .
            ($apellido_1 == false ? ' ' : $apellido_1) . ' ' .
            ($apellido_2 == false ? ' ' : $apellido_2);
        if ($tramiteData[0]->nombre_solicitante_oficial == '') {
            TramiteConstruccion::where('folio', $folio)->update(['nombre_solicitante_oficial' => $nombre_formado]);
        }
        $data = ['nombre' => $nombre_formado, 'email' => $email];
        return $data;
    }
    public function getNombreSolicitanteRefrendo($folio)
    {

        $tramiteData = TramiteConstruccion::where('id_consulta_requisitos', $folio)->get();
        $consultaData = ConsultaRequisitoConstruccion::where('id', $folio)->get();
        if (empty($consultaData)) {
            return false;
        }

        $caracter = $consultaData[0]->caracter_solicitante;
        $tipo_persona = $consultaData[0]->tipo_persona;
        $id_tramite = $consultaData[0]->id;
        $nombre_persona = '';
        $apellido_1 = '';
        $apellido_2 = '';
        $email = '';
        switch ($caracter) {
            case 'Carta poder':
                $nombre_persona = $this->getRespuesta($id_tramite, 'nombre_apoderado');
                $apellido_1 = $this->getRespuesta($id_tramite, 'apellido_1_apoderado');
                $apellido_2 = $this->getRespuesta($id_tramite, 'apellido_2_apoderado');
                $email = $this->getRespuesta($id_tramite, 'correo_electronico_apoderado');
                break;
            case 'Propietario':
                $nombre_persona = $this->getRespuesta($id_tramite, ($tipo_persona == 'Moral' ? 'nombre_apoderado_moral' : 'nombre_propietario'));
                $apellido_1 = $this->getRespuesta($id_tramite, ($tipo_persona == 'Moral' ? 'apellido_1_apoderado_moral' : 'apellido_1_propietario'));
                $apellido_2 = $this->getRespuesta($id_tramite, ($tipo_persona == 'Moral' ? 'apellido_2_apoderado_moral' : 'apellido_2_propietario'));
                $email = $this->getRespuesta($id_tramite, ($tipo_persona == 'Moral' ? 'correo_electronico_apoderado_moral' : 'correo_electronico_propietario'));
                break;
            case 'Arrendatario':
                $nombre_persona = $this->getRespuesta($id_tramite, ($tipo_persona == 'Moral' ? 'nombre_apoderado_moral' : 'nombre_arrendatario'));
                $apellido_1 = $this->getRespuesta($id_tramite, ($tipo_persona == 'Moral' ? 'apellido_1_apoderado_moral' : 'apellido_1_arrendatario'));
                $apellido_2 = $this->getRespuesta($id_tramite, ($tipo_persona == 'Moral' ? 'apellido_2_apoderado_moral' : 'apellido_2_arrendatario'));
                $email = $this->getRespuesta($id_tramite, ($tipo_persona == 'Moral' ? 'correo_electronico_apoderado_moral' : 'correo_electronico_arrendatario'));
                break;
        }
        //  die();
        $nombre_formado = ($nombre_persona == false ? ' ' : $nombre_persona) . ' ' .
            ($apellido_1 == false ? ' ' : $apellido_1) . ' ' .
            ($apellido_2 == false ? ' ' : $apellido_2);
        if ($tramiteData[0]->nombre_solicitante_oficial == '') {
            TramiteConstruccion::where('id_consulta_requisitos', $folio)->update(['nombre_solicitante_oficial' => $nombre_formado]);
        }
        $data = ['nombre' => $nombre_formado, 'email' => $email];
        return $data;
    }

    public function getListadoLicenciasVisor(string $folio = '')
    {
        $user = Auth::user();
        $role_id = Auth::user()->roles_construccion[0]->id;
        $data = DB::table('licencias_construccion_visor')
            ->join('consulta_requisitos_construccion', 'consulta_requisitos_construccion.folio', '=', 'licencias_construccion_visor.folio')
            ->join('tramite_construccion', 'consulta_requisitos_construccion.folio', '=', 'tramite_construccion.folio')
            ->join('tipo_tramites_construccion', 'consulta_requisitos_construccion.tramite_relacionado', '=', 'tipo_tramites_construccion.id')
            ->select('consulta_requisitos_construccion.*', 'consulta_requisitos_construccion.id as ids', 'licencias_construccion_visor.*', 'licencias_construccion_visor.created_at as fecha_emision', 'tipo_tramites_construccion.tramite as tramite_relacionado_nombre', 'tramite_construccion.direccion_tramite as domicilio_tramite')
            ->where('consulta_requisitos_construccion.id_municipio', $user->id_municipio)
            ->where(function ($q) use ($folio) {
                $q->where('licencias_construccion_visor.folio', 'ilike', '%' . $folio . '%')
                    ->orWhere('licencias_construccion_visor.numero_lic', 'ilike', '%' . $folio . '%')
                    ->orWhere('consulta_requisitos_construccion.calle', 'ilike', '%' . $folio . '%')
                    ->orWhere('licencias_construccion_visor.consecutivo', 'ilike', '%' . $folio . '%')
                    ->orWhere(function ($r) use ($folio) {
                        $r->where(DB::raw("CONCAT(licencias_construccion_visor.dueno, ' ', licencias_construccion_visor.apellido_p, ' ', licencias_construccion_visor.apellido_m)"), 'ilike', '%' . $folio . '%');
                    });
            })->orderBy('licencias_construccion_visor.id', 'DESC')->get();

        foreach ($data as $value) {
            $nombre = DB::table('respuestas_construccion')->select('value')->where('name', 'int_nombre')->where('id_tramite', $value->ids)->get();
            $apellido_1 = DB::table('respuestas_construccion')->select('value')->where('name', 'int_apellidouno')->where('id_tramite', $value->ids)->get();
            $apellido_2 = DB::table('respuestas_construccion')->select('value')->where('name', 'int_apellidodos')->where('id_tramite', $value->ids)->get();

            $calle = DB::table('respuestas_construccion')->select('value')->where('name', 'obra_calle')->where('id_tramite', $value->ids)->get();
            $colonia = DB::table('respuestas_construccion')->select('value')->where('name', 'obra_colonia')->where('id_tramite', $value->ids)->get();
            $value->calle = $calle[0]->value;
            $value->colonia = $colonia[0]->value;
            $value->nombre_solicitante = $nombre[0]->value . " " . $apellido_1[0]->value . " " . $apellido_2[0]->value;
        }

        $pagina = new Paginator($data, 100);

        return $pagina;
    }

    public function getDueno($folio)
    {
        $tramiteData = TramiteConstruccion::where('folio', $folio)->get();
        $consultaData = ConsultaRequisitoConstruccion::where('folio', $folio)->get();
        if (empty($consultaData)) {
            return false;
        }
        $id_tramite = $consultaData[0]->id;
        $tipo_persona = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'int_tipo_persona')->get();
        $tipo_persona = $tipo_persona[0]->value;
        $nombre_persona = '';
        $apellido_1 = '';
        $apellido_2 = '';

        $nombre_persona = $this->getRespuesta($id_tramite, ('int_nombre'));
        $apellido_1 = $this->getRespuesta($id_tramite, ('int_apellidouno'));
        $apellido_2 = $this->getRespuesta($id_tramite, ('int_apellidodos'));

        $nombre_formado = ($nombre_persona == false ? ' ' : $nombre_persona) . ' ' .
            ($apellido_1 == false ? ' ' : $apellido_1) . ' ' .
            ($apellido_2 == false ? ' ' : $apellido_2);
        if ($tramiteData[0]->nombre_solicitante_oficial == '') {
            TramiteConstruccion::where('folio', $folio)->update(['nombre_solicitante_oficial' => $nombre_formado]);
        }
        return $nombre_formado;
    }

    public function getDataDueno($folio)
    {
        $tramiteData = TramiteConstruccion::where('folio', $folio)->first();
        $consultaData = ConsultaRequisitoConstruccion::where('folio', $folio)->first();

        if (!$consultaData) {
            return false;
        }
        $id_tramite = $consultaData->id;
        $fields = [
            'int_tipo_persona', 'prop_curp', 'prop_cel', 'prop_correo', 'obra_cp', 'obra_cve_catastral',
            'obra_cta_predial', 'tipo_dcto_propiedad', 'no_dcto_propiedad', 'fedatario_publico',
            'fecha_dcto_propiedad', 'lic_vigencia', 'exp_uso_suelo', 'dro_no_registro', 'dro_correo',
            'dro_cel', 'dro_nombre', 'dro_apellidouno', 'dro_apellidodos', 'int_calle', 'int_no_ext',
            'int_no_int', 'lic_restri_pos', 'lic_restri_trasera', 'lic_restri_lat', 'int_nombre',
            'int_apellidouno', 'int_apellidodos', 'prop_nombre', 'prop_apellidouno', 'prop_apellidodos', 'prop_calle', 
            'prop_no_ext', 'prop_no_int'
        ];
        $values = RespuestaConstruccion::select('name', 'value')
            ->where('id_tramite', $id_tramite)
            ->whereIn('name', $fields)
            ->get()
            ->keyBy('name')
            ->map(function ($item) {
                return $item->value;
            })
            ->toArray();
        

            $values['fedatario'] =  $this->getOptionValue($values, 'fedatario_publico');
            $values['tipo_dcto_propiedad'] =  $this->getOptionValue($values, 'tipo_dcto_propiedad');
         

        $values['direccion'] = "{$values['int_calle']} número {$values['int_no_ext']} int. {$values['int_no_int']}";
        $values['direccion_prop'] = "{$values['prop_calle']} número {$values['prop_no_ext']} int. {$values['prop_no_int']}";
        $values['nombre_completo'] = trim("{$values['int_nombre']} {$values['int_apellidouno']} {$values['int_apellidodos']}");
        $values['nombre_completo_prop'] = trim("{$values['prop_nombre']} {$values['prop_apellidouno']} {$values['prop_apellidodos']}");
        if (empty($tramiteData->nombre_solicitante_oficial)) {
            TramiteConstruccion::where('folio', $folio)->update(['nombre_solicitante_oficial' => $values['nombre_completo']]);
        }
        return $values;
    }

    function getOptionValue($values, $name) {
        $optionString = CampoConstruccion::where('name', $name)->value('opciones');
        $optionIndex = $values[$name];
        if ($optionString && $optionIndex) {
            $options = explode("|", $optionString);
            $index = $optionIndex - 1;
            return $options[$index] ?? null;
        }
        return null;
    }
    

    /*
    public function getDataDueno($folio)
    {
    $tramiteData =  TramiteConstruccion::where('folio', $folio)->get();
    $consultaData =  ConsultaRequisitoConstruccion::where('folio', $folio)->get();
    if (empty($consultaData)) {
    return false;
    }
    $id_tramite = $consultaData[0]->id;
    $tipo_persona = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'int_tipo_persona')->get();
    $curp = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'int_curp')->get();
    $tipo_persona = $tipo_persona[0]->value;
    $curp = $curp[0]->value;
    $nombre_persona = '';
    $apellido_1 = '';
    $apellido_2 = '';
    $cel = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'int_cel')->get();
    $cel = $cel[0]->value;
    $correo = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'int_correo')->get();
    $correo = $correo[0]->value;
    $cp = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'obra_cp')->get();
    $cp = $cp[0]->value;
    $obra_cve_catastral = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'obra_cve_catastral')->get();
    $obra_cve_catastral = $obra_cve_catastral[0]->value;
    $obra_cta_predial = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'obra_cta_predial')->get();
    $obra_cta_predial = $obra_cta_predial[0]->value;
    $tipo_dcto_propiedad = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'tipo_dcto_propiedad')->get();
    $tipo_dcto_propiedad = $tipo_dcto_propiedad[0]->value;
    $no_dcto_propiedad= RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'no_dcto_propiedad')->get();
    $no_dcto_propiedad = $no_dcto_propiedad[0]->value;
    $fedatario_publico= RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'fedatario_publico')->get();
    $fedatario_publico = $fedatario_publico[0]->value;
    $fecha_dcto_propiedad= RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'fecha_dcto_propiedad')->get();
    $fecha_dcto_propiedad = $fecha_dcto_propiedad[0]->value;

    $lic_vigencia= RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'lic_vigencia')->get();
    $lic_vigencia = $lic_vigencia[0]->value;
    $exp_uso_suelo= RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'exp_uso_suelo')->get();
    $exp_uso_suelo = $exp_uso_suelo[0]->value;
    $dro_no_registro= RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'dro_no_registro')->get();
    $dro_no_registro = $dro_no_registro[0]->value;
    $dro_correo= RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'dro_correo')->get();
    $dro_correo = $dro_correo[0]->value;
    $dro_cel= RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'dro_cel')->get();
    $dro_cel = $dro_cel[0]->value;
    $dro_nombre = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'dro_nombre')->get();
    $dro_nombre = $dro_nombre[0]->value;
    $dro_apellido_p = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'dro_apellidouno')->get();
    $dro_apellido_p = $dro_apellido_p[0]->value;
    $dro_apellido_m = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'dro_apellidodos')->get();
    $dro_apellido_m = $dro_apellido_m[0]->value;
    $dro_nombre_completo = $dro_nombre. ' ' . $dro_apellido_p . ' ' .  $dro_apellido_m;
    $int_calle = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'int_calle')->get();
    $int_calle = $int_calle[0]->value;
    $int_no_ext = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'int_no_ext')->get();
    $int_no_ext = $int_no_ext[0]->value;
    $int_no_int = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'int_no_int')->get();
    $int_no_int = $int_no_int[0]->value;
    $direccion = $int_calle . ' número ' . $int_no_ext . ' int. ' . $int_no_int;

    $restri_pos = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'lic_restri_pos')->get();
    $restri_pos = $restri_pos[0]->value;

    $restri_trasera = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'lic_restri_trasera')->get();
    $restri_trasera = $restri_trasera[0]->value;
    $restri_lat = RespuestaConstruccion::where('id_tramite', $id_tramite)->where('name', 'lic_restri_lat')->get();
    $restri_lat = $restri_lat[0]->value;

    $nombre_persona = $this->getRespuesta($id_tramite, ('int_nombre'));
    $apellido_1 = $this->getRespuesta($id_tramite, ('int_apellidouno'));
    $apellido_2 = $this->getRespuesta($id_tramite, ('int_apellidodos'));

    $nombre_formado = ($nombre_persona == false ? ' ' : $nombre_persona) . ' ' .
    ($apellido_1 == false ? ' ' : $apellido_1) . ' ' .
    ($apellido_2 == false ? ' ' : $apellido_2);
    if ($tramiteData[0]->nombre_solicitante_oficial == '') {
    TramiteConstruccion::where('folio', $folio)->update(['nombre_solicitante_oficial' => $nombre_formado]);
    }
    return array('nombre_completo' => $nombre_formado, 'nombre' => $nombre_persona,'apellido_p'=>$apellido_1,'apellido_m'=>$apellido_2,'curp'=>$curp,'tipo_persona'=>$tipo_persona, 'celular'=>$cel, 'correo'=>$correo, 'cp'=>$cp, 'obra_cve_catastral'=>$obra_cve_catastral, 'obra_cta_predial'=>$obra_cta_predial, 'tipo_dcto_propiedad'=> $tipo_dcto_propiedad, 'no_dcto_propiedad'=> $no_dcto_propiedad, 'fedatario_publico'=> $fedatario_publico, 'fecha_dcto_propiedad'=>$fecha_dcto_propiedad, 'lic_vigencia'=>$lic_vigencia, 'exp_uso_suelo'=>$exp_uso_suelo, 'dro_no_registro'=>$dro_no_registro, 'dro_correo'=>$dro_correo, 'dro_cel'=>$dro_cel, 'dro_nombre_completo'=> $dro_nombre_completo, 'direccion'=> $direccion, 'restri_pos'=>$restri_pos, 'restri_trasera'=>$restri_trasera, 'restri_lat'=>$restri_lat);
    }*/

    public function getDataDueno2($folio)
    {
        $tramiteData = TramiteConstruccion::where('folio', $folio)->get();

        $consultaData = ConsultaRequisitoConstruccion::where('folio', $folio)->get();
        if (empty($consultaData)) {
            return false;
        }
        $id_tramite = $consultaData[0]->id;

        $tipo_persona = $this->getRespuesta($id_tramite, 'int_tipo_persona');

        if ($tipo_persona == 'Moral') {
            $nombre_persona = $this->getRespuesta($id_tramite, ('int_moral_rsocial'));
            $apellido_1 = null;
            $apellido_2 = null;
        } else {
            $nombre_persona = $this->getRespuesta($id_tramite, ('int_nombre'));
            $apellido_1 = $this->getRespuesta($id_tramite, ('int_apellidouno'));
            $apellido_2 = $this->getRespuesta($id_tramite, ('int_apellidodos'));
        }
        $curp = $this->getRespuesta($id_tramite, 'int_curp');

        $nombre_formado = ($nombre_persona == false ? ' ' : $nombre_persona) . ' ' .
            ($apellido_1 == false ? ' ' : $apellido_1) . ' ' .
            ($apellido_2 == false ? ' ' : $apellido_2);
        if ($tramiteData[0]->nombre_solicitante_oficial == '') {
            TramiteConstruccion::where('folio', $folio)->update(['nombre_solicitante_oficial' => $nombre_formado]);
        }
        return array('nombre' => $nombre_persona ? $nombre_persona : 'N/A', 'apellido_p' => $apellido_1, 'apellido_m' => $apellido_2, 'curp' => $curp, 'caracter' => $tipo_persona);
    }

    public function getRespuesta($id_tramite, $name)
    {
        $respuesta = RespuestaConstruccion::where(['id_tramite' => $id_tramite, 'name' => $name])->get();

        if (empty($respuesta)) {
            return false;
        } else {
            return $respuesta[0]->value;
        }
    }
    public function store(TramiteCreateDto $store): Product
    {
        $entry = new Product();
        $entry->sku = $store->sku;
        $entry->name = $store->name;
        $entry->description = $store->description;
        $entry->price = $store->price;
        $entry->save();

        return $entry;
    }

    public function update(TramiteUpdateDto $store): void
    {
        $entry = Product::find($store->id);

        $entry->sku = $store->sku;
        $entry->name = $store->name;
        $entry->description = $store->description;
        $entry->price = $store->price;

        $entry->save();
    }

    public function image(int $id, UploadedFile $file): void
    {
        $entry = Product::find($id);
        if ($entry) {
            // filename
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            // path
            $path = app()->basePath('public/images/');
            // upload
            $file->move($path, $filename);
            $entry->image = URL::to('images/' . $filename);
            // save changes
            $entry->save();
        } else {
            throw new Exception("Entry wasn't found by " . $id);
        }
    }

    public function destroy(int $id): void
    {
        Product::destroy($id);
    }

}
