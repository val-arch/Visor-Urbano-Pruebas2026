<?php

namespace App\Repositories;

use Exception;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use App\DTOs\Tramites\TramiteCreateDto;
use App\DTOs\Tramites\TramiteUpdateDto;
use App\Models\Tramite;
use App\Models\ConsultaRequisito;
use App\Models\Respuesta;
use App\Models\Revisiones;
use App\Models\Municipio;
use App\Models\AperturaProvisional;
use App\Models\Giro;
use App\Models\HistoricoLicencias;
use App\Models\Refrendo;
use Carbon\Carbon;
use App\Repositories\Interfaces\ITramiteRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Mail\ingresoSinFirmaGirosMailDependencia;
use App\Mail\ingresoSinFirmaGirosMailCiudadano;
use Illuminate\Support\Facades\Mail;

class TramiteRepository implements ITramiteRepository
{
    public function paginate(int $take): Paginator
    {
        return Product::orderBy('name')
            //   ->where('price', '>=', 80)
            //   ->where('price', '<=', 100)
            ->whereBetween('price', [80, 100])
            ->simplePaginate($take);
    }

    public function find(int $id): ?Product
    {
        return Product::find($id);
    }

    public function ingresoRefrendo(string $folio)
    {
        $RefrendoTramite =  Refrendo::where('id', $folio)->firstOrFail();

        $idRefrendo=$RefrendoTramite->id_consulta_requisitos;

        $tramiteData =  Tramite::where('id_consulta_requisitos', $idRefrendo)->firstOrFail();


        $tramiteData->folio;
        $mytime = Carbon::now();
        $mytime->toDateTimeString();
        $role_id =  Auth::user()->roles[0]->id;
        $step_actual = $tramiteData->step_actual;

        if ($step_actual ==  0) {
            if ($role_id  ==  2) {
                $entry       =  Tramite::where('id_consulta_requisitos',  $idRefrendo);
                $entry->step_actual            = 1;
                $entry->fecha_visto_ventanilla = $mytime;
                $entry->rol_ingreso            = $role_id;
            } else {

                $entry       =  Tramite::where('id_consulta_requisitos',  $idRefrendo);
                $entry->step_actual          = 1;
                $entry->fecha_inicio_tramite = $mytime;
                $entry->rol_ingreso = $role_id;
            }
        }

        return   $tramiteData;
    }

    public function ingreso(string $folio)
    {

        $tramiteData =  Tramite::where('folio', $folio)->firstOrFail();
        $tramiteData->folio;
        $mytime = Carbon::now();
        $mytime->toDateTimeString();
        $role_id =  Auth::user()->roles[0]->id;
        $step_actual = $tramiteData->step_actual;

        if ($step_actual ==  0) {
            if ($role_id  ==  2) {
                $entry       =  Tramite::where('folio',  $folio);
                $entry->step_actual            = 1;
                $entry->fecha_visto_ventanilla = $mytime;
                $entry->rol_ingreso            = $role_id;
            } else {

                $entry       =  Tramite::where('folio',  $folio);
                $entry->step_actual          = 1;
                $entry->fecha_inicio_tramite = $mytime;
                $entry->rol_ingreso = $role_id;
            }
        }

        return   $tramiteData;
    }

    public function noFirmaElectronica(string $folio)
    {
        $tramiteData =  Tramite::where('folio', $folio)->first();
        $user =  Auth::user();
        $role_id =  Auth::user()->roles[0]->id;
        if ($user->id == $tramiteData->id_usuario) {
            $t = Tramite::find($tramiteData->id);
            $t->tengo_firma = 0;
            $t->fecha_no_firma =  Carbon::now();
            $t->save();
            $data = ['acuse_no_firma' => 'acuseNoFirmado/' . base64_encode($folio)];


            $consulta = ConsultaRequisito::where('folio', $folio)->get();
            $municipio = Municipio::where('id', $consulta[0]->id_municipio)->get();
            $municipio_nombre = $municipio[0]->nombre;
            $folio_requisitos = $consulta[0]->folio;
            //Mail::to()->send(new ingresoSinFirmaGirosMailCiudadano($municipio));
            Mail::to($municipio[0]->correo_dependencia)->send(new ingresoSinFirmaGirosMailDependencia($folio_requisitos));


            return  $data;
        } else {
            return false;
        }
    }

    public function ingresoTramiteGenerarLicencia(string $folio)
    {
        $tramiteData =  Tramite::where('folio', $folio)->get();
        if($tramiteData[0]->enviado_revisores == 1){
            return "tramite_duplicado";
        }
        $consultaData =  ConsultaRequisito::where('folio', $folio)->get();
        $user =  Auth::user();
        $role_id =  $user->roles[0]->id;
        $gioOf = DB::table('giros')->where(['codigo'=>$consultaData[0]->codigo_scian])->get();
        $giro_cedula = DB::table('giros_configuracion')->where(['giros_id'=>$gioOf[0]->id,'municipios_id'=>$consultaData[0]->id_municipio,'giro_cedula'=>1])->get();

        if( count($giro_cedula) == 0)
        {
            $urlAperturaProv = false;
            $response['message'] = 'Para poder emitirla, es necesario que el Director active la emisión de la Cédula de Apertura Provisional en este giro.';
            $response['status'] = false;
            return $response;
        }
        if ($role_id > 1) {
            $t = Tramite::find($tramiteData[0]->id);
            $t->enviado_revisores = 1;
            $t->fecha_enviado_revisores = Carbon::now();
            $t->licencia_generada_ventanilla = $user->id;
            $t->save();
            $revisores = [3,4];
            $campos = DB::table('campos')
                ->join('respuestas', 'campos.name', '=', 'respuestas.name')
                ->where('respuestas.id_tramite', $consultaData[0]->id)
                ->where('campos.type', 'file')
                ->whereNotNull('campos.condicion_dependencia')
                ->select('campos.*', 'respuestas.value')
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
                case 2: //ventanilla
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
                if($value){
                    Revisiones::create([
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

            $mun =  Municipio::find($consultaData[0]->id_municipio);
            $urlAperturaProv = true;

            if ($urlAperturaProv) {
                $response['data'] = $t;
                $response['message'] = 'No se puede emitir la licencia, clic en continuar';
                $response['status'] = true;
                return $response;
            }else{
                return false;
            }
        }
    }

    public function ingresoTramiteContinuar(string $folio)
    {
        $tramiteData =  Tramite::where('folio', $folio)->get();
        $consultaData =  ConsultaRequisito::where('folio', $folio)->get();
        $user =  Auth::user();
        $role_id =  $user->roles[0]->id;
        if (($role_id > 1) || ($user->id == $tramiteData[0]->id_usuario &&  $tramiteData[0]->firma_usuario != '')) {

            $t = Tramite::find($tramiteData[0]->id);
            $t->enviado_revisores = 1;
            $t->fecha_enviado_revisores =  Carbon::now();
            $t->save();
            $revisores = [3];
            $campos = DB::table('campos')
                ->join('respuestas', 'campos.name', '=', 'respuestas.name')
                ->where('respuestas.id_tramite', $consultaData[0]->id)
                ->where('campos.type', 'file')
                ->whereNotNull('campos.condicion_dependencia')
                ->select('campos.*', 'respuestas.value')
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
            foreach ($revisores as $value) {
                // return $value;
                if($value){
                    Revisiones::create([
                        'id_tramite' => $consultaData[0]->id,
                        'id_municipio' => $consultaData[0]->id_municipio,
                        'folio' => $folio,
                        'id_usuario' => 0,
                        'rol' => $value,
                        'fecha_inicio' => Carbon::now(),
                        'fecha_actualizacion' => Carbon::now()
                    ]);
                }

            }
            //return $insertRevisores;
            //  print_r($insertRevisores);
            // die();


            $mun =  Municipio::find($consultaData[0]->id_municipio);
            $urlAperturaProv = true;
            $restricciones =  $consultaData[0]->restricciones;
            $restricciones = json_decode($restricciones, true);
            $gioOf = DB::table('giros')->where(['codigo'=>$consultaData[0]->codigo_scian])->get();

            $giro_cedula = DB::table('giros_configuracion')->where(['giros_id'=>$gioOf[0]->id,'municipios_id'=>$consultaData[0]->id_municipio,'giro_cedula'=>1])->get();

            if( count($giro_cedula) == 0 ||
                $restricciones['escuelas'] == 1  ||
                $restricciones['centros_salud'] == 1 ||
                $restricciones['edificios_gobierno'] == 1  ||
                $restricciones['bloque_construccion_actividad'] == 1  ||
                $restricciones['cuerpos_agua'] == 1 ){
                        $urlAperturaProv = false;
                    }
            if ($urlAperturaProv) {
                $apertura =  AperturaProvisional::where(['folio' => $folio, 'contador' => 1])->get();
                if (count($apertura) == 0) {
                    $ap = new AperturaProvisional;
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
                $data['acuse_firma_electronica_url'] = 'acuseFirmado/' . base64_encode($folio);
            }
            if($role_id>1){
                $data['acuse_Ventanilla'] = 'acuseVentanilla/' . base64_encode($folio);
            }

            return  $data;
        } else {
            return false;
        }

    }

    public function getListadoDesechados(string $folio = '')
    {
        $user =  Auth::user();
        $role_id =  Auth::user()->roles[0]->id;

        $data = DB::table('consulta_requisitos')
                    ->join('tramite', 'consulta_requisitos.folio', '=', 'tramite.folio')
                    ->select('*')
                    ->where('tramite.desechado', true)
                    ->where('consulta_requisitos.id_municipio', $user->id_municipio);
                if ($folio != '') {
                    $data->where('consulta_requisitos', 'like', "%$folio%");
                }

                $data = $data->orderBy('consulta_requisitos.id', 'desc')
                    ->paginate(10);
        if ($data) {
            return $data;
        } else {
            return false;
        }


    }

    public function getListado(string $folio = '')
    {

        $user =  Auth::user();
        $role_id =  Auth::user()->roles[0]->id;

        switch ($role_id) {
            case 1:
                $data = DB::table('consulta_requisitos')
                    ->join('tramite', 'consulta_requisitos.folio', '=', 'tramite.folio')
                    ->select('*')
                    ->orderBy('consulta_requisitos.id', 'desc')
                    ->where('tramite.id_usuario',$user->id)
                    ->where('tramite.desechado', false)
                    ->paginate(20);
                break;
            case 2:
                $data = DB::table('consulta_requisitos')
                    ->join('tramite', 'consulta_requisitos.folio', '=', 'tramite.folio')
                    ->select('consulta_requisitos.*','tramite.enviado_revisores')
                    ->where('consulta_requisitos.id_municipio', $user->id_municipio)
                    ->where('tramite.desechado', false)
                    ->where('tramite.tengo_firma', 0)
                    ->orWhere('tramite.id_usuario','=',0)
                    ->where('consulta_requisitos.id_municipio', $user->id_municipio);
                if ($folio != '') {
                    $data->where('consulta_requisitos', 'like', "%$folio%");
                }
                $data = $data->orderBy('consulta_requisitos.id', 'desc')
                    ->paginate(20);
                break;
            case 3:
                $data = DB::table('consulta_requisitos')
                    ->join('tramite', 'consulta_requisitos.folio', '=', 'tramite.folio')
                    ->join('revisiones_dependencias', 'consulta_requisitos.folio', '=', 'revisiones_dependencias.folio')
                    ->select('*', DB::raw('(select count(archivos) from solventacion where solventacion.id_tramite=consulta_requisitos.id) as solventacion_ciudadano'))
                    ->where('revisiones_dependencias.rol', 3)
                    ->where('tramite.desechado', false)
                    ->where('consulta_requisitos.id_municipio', $user->id_municipio);
                if ($folio != '') {
                    $data->where('consulta_requisitos', 'like', "%$folio%");
                }
                // $data = $data->se
                $data = $data->orderBy('consulta_requisitos.id', 'desc')
                    ->paginate(20);
                break;
            case 4:
                $data = DB::table('consulta_requisitos')
                    ->join('tramite', 'consulta_requisitos.folio', '=', 'tramite.folio')
                    ->join('revisiones_dependencias', 'consulta_requisitos.folio', '=', 'revisiones_dependencias.folio')
                    ->select('*', DB::raw('(select count(archivos) from solventacion where solventacion.id_tramite=consulta_requisitos.id) as solventacion_ciudadano'))
                    ->where('revisiones_dependencias.rol', $role_id)
                    ->where('tramite.desechado', false)
                    ->where('consulta_requisitos.id_municipio', $user->id_municipio);
                if ($folio != '') {
                    error_log("aqui");
                    $data->where('consulta_requisitos', 'like', "%$folio%");
                }

                $data = $data->orderBy('consulta_requisitos.id', 'desc')
                    ->paginate(10);
                break;
                default:
                    $data = DB::table('consulta_requisitos')
                        ->join('tramite', 'consulta_requisitos.folio', '=', 'tramite.folio')
                        ->join('revisiones_dependencias', 'consulta_requisitos.folio', '=', 'revisiones_dependencias.folio')
                        ->select('*', DB::raw('(select count(archivos) from solventacion where solventacion.id_tramite=consulta_requisitos.id) as solventacion_ciudadano'))
                        ->where('revisiones_dependencias.rol', $role_id)
                        ->where('tramite.desechado', false)
                        ->where('consulta_requisitos.id_municipio', $user->id_municipio);
                    if ($folio != '') {
                        $data->where('consulta_requisitos', 'like', "%$folio%");
                    }
                    // $data = $data->se
                    $data = $data->orderBy('consulta_requisitos.id', 'desc')
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

        $user =  Auth::user();
        $role_id =  $user->roles[0]->id;
        if($role_id < 2){
            return false;
        }

                $data = DB::table('consulta_requisitos')
                    ->join('tramite', 'consulta_requisitos.folio', '=', 'tramite.folio')
                    ->select('consulta_requisitos.*','tramite.enviado_revisores')
                    ->where('consulta_requisitos.id_municipio', $user->id_municipio)
                    ->where('tramite.tengo_firma', 0)
                    ->orWhere('tramite.id_usuario','=',0)
                    ->where('consulta_requisitos.id_municipio', $user->id_municipio)
                    ->where('tramite.desechado', false);
                if ($folio != '') {
                    $data->where('consulta_requisitos', 'like', "%$folio%");
                }
                $data = $data->orderBy('consulta_requisitos.id', 'desc')

                    ->paginate(20);

        if ($data) {
            return $data;
        } else {
            return false;
        }
    }

    public function getListadoDirRev(string $folio = '')
    {

        $user =  Auth::user();
        $role_id =  $user->roles[0]->id;
        if($role_id!=4){
            return false;
        }
        $data = DB::table('consulta_requisitos')
        ->join('tramite', 'consulta_requisitos.folio', '=', 'tramite.folio')
        ->join('revisiones_dependencias', 'consulta_requisitos.folio', '=', 'revisiones_dependencias.folio')
        ->select('*', DB::raw('(select count(archivos) from solventacion where solventacion.id_tramite=consulta_requisitos.id) as solventacion_ciudadano'))
        ->where('revisiones_dependencias.rol', 3)
        ->where('tramite.desechado', false)
        ->where('consulta_requisitos.id_municipio', $user->id_municipio);
        if ($folio != '') {
            $data->where('consulta_requisitos', 'like', "%$folio%");
        }
        // $data = $data->se
        $data = $data->orderBy('consulta_requisitos.id', 'desc')
        ->paginate(20);

        if ($data) {
            return $data;
        } else {
            return false;
        }
    }
    public function getListadoDirSolv(string $folio = '')
    {

        $user =  Auth::user();
        $role_id =  $user->roles[0]->id;
        if($role_id<2){
            return false;
        }
        $data = DB::table('consulta_requisitos')->select('tramite.*','consulta_requisitos.*','notificaciones.id as noti_id')
        ->join('tramite', 'consulta_requisitos.folio', '=', 'tramite.folio')
        ->join('notificaciones', 'consulta_requisitos.folio', '=', 'notificaciones.folio')
        ->where('tramite.aprobado_director','!=', 1)
        ->where('tramite.desechado', false)
        ->where('notificaciones.id_solventacion','!=', 0)
        ->where('consulta_requisitos.id_municipio', $user->id_municipio);
        if ($folio != '') {
            $data->where('consulta_requisitos', 'like', "%$folio%");
        }
        // $data = $data->se
        $data = $data->orderBy('notificaciones.id', 'desc')
        ->paginate(20);

        if ($data) {
            return $data;
        } else {
            return false;
        }
    }

    public function getNombreSolicitante($folio)
    {
        $tramiteData =  Tramite::where('folio', $folio)->get();
        $consultaData =  ConsultaRequisito::where('folio', $folio)->get();
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
            Tramite::where('folio', $folio)->update(['nombre_solicitante_oficial' => $nombre_formado]);
        }
        $data = ['nombre' => $nombre_formado, 'email' => $email];
        return $data;
    }
    public function getNombreSolicitanteRefrendo($folio)
    {

        $tramiteData =  Tramite::where('id_consulta_requisitos', $folio)->get();
        $consultaData =  ConsultaRequisito::where('id', $folio)->get();
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
            Tramite::where('id_consulta_requisitos', $folio)->update(['nombre_solicitante_oficial' => $nombre_formado]);
        }
        $data = ['nombre' => $nombre_formado, 'email' => $email];
        return $data;
    }

    public function getListadoLicenciasVisor(string $folio = '')
    {
        $validOptions = ['Cancelada', 'Suspendida', 'Vigente', 'Baja', 'Sanción', 'Adeudo', 'Renovar'];

        $user =  Auth::user();
        $role_id =  Auth::user()->roles[0]->id;
        $data = DB::table('consulta_requisitos')
                    ->join('licencias_giro_visor', 'consulta_requisitos.folio', '=', 'licencias_giro_visor.folio')
                    ->select('consulta_requisitos.*','consulta_requisitos.id as ids','licencias_giro_visor.*','licencias_giro_visor.created_at as fecha_emision')
                    ->where('consulta_requisitos.id_municipio', $user->id_municipio);

                if ($folio != '') {
                    if (in_array($folio, $validOptions)) {
                        $folio_temp = str_replace(' ', '', $folio);
                        $data->where(function($query) use ($folio, $folio_temp){
                            $query->where('licencias_giro_visor.status_licencia', 'like', "%$folio%");
                        });
                    }else{
                        $folio_temp = str_replace(' ', '', $folio);

                        $data->where(function($query) use ($folio, $folio_temp){
                            $query->where('consulta_requisitos.folio', 'like', "%$folio%");
                            $query->orWhereRaw('upper(unaccent("consulta_requisitos"."nombre_scian")) ilike \'%'.strtoupper($folio).'%\'');
                            $query->orWhereRaw('upper(unaccent("consulta_requisitos"."nombre_solicitante")) ilike \'%'.strtoupper($folio).'%\'');
                            $query->orWhereRaw('upper(unaccent("consulta_requisitos"."calle")) ilike \'%'.strtoupper($folio).'%\'');
                            $query->orWhereRaw('upper(unaccent("licencias_giro_visor"."actividad_comercial")) ilike \'%'.strtoupper($folio).'%\'');
                            $query->orWhere('licencias_giro_visor.numero_lic', 'ilike', "%$folio%");
                            $query->orWhereRaw('upper(unaccent(concat("dueno", "apellido_p", "apellido_m"))) ilike \'%'.strtoupper($folio_temp).'%\'');

                        });
                    }
                }
                $data = $data
                ->orderBy('licencias_giro_visor.created_at', 'desc')
                ->orderBy('consulta_requisitos.id', 'desc')
                ->paginate(10);

                return $data;
    }

    public function getDueno($folio)
    {
        $tramiteData =  Tramite::where('folio', $folio)->get();
        $consultaData =  ConsultaRequisito::where('folio', $folio)->get();
        if (empty($consultaData)) {
            return false;
        }
        $caracter = $consultaData[0]->caracter_solicitante;
        $tipo_persona = $consultaData[0]->tipo_persona;
        $id_tramite = $consultaData[0]->id;
        $nombre_persona = '';
        $apellido_1 = '';
        $apellido_2 = '';
        switch ($caracter) {
            case 'Carta poder':
                if ($tipo_persona == 'Moral') {
                    $nombre_persona = $this->getRespuesta($id_tramite, ('razon_social_dueno_moral'));
                } else {
                    $nombre_persona = $this->getRespuesta($id_tramite, ('nombre_dueno_negocio'));
                    $apellido_1 = $this->getRespuesta($id_tramite, ('apellido_1_dueno_negocio'));
                    $apellido_2 = $this->getRespuesta($id_tramite, ('apellido_2_dueno_negocio'));
                }
                break;
            case 'Propietario':
                if ($tipo_persona == 'Moral') {
                    $nombre_persona = $this->getRespuesta($id_tramite, ('razon_social'));
                } else {
                    $nombre_persona = $this->getRespuesta($id_tramite, ('nombre_propietario'));
                    $apellido_1 = $this->getRespuesta($id_tramite, ('apellido_1_propietario'));
                    $apellido_2 = $this->getRespuesta($id_tramite, ('apellido_2_propietario'));
                }
                break;
            case 'Arrendatario':
                if ($tipo_persona == 'Moral') {
                    $nombre_persona = $this->getRespuesta($id_tramite, ('razon_social'));
                } else {
                    $nombre_persona = $this->getRespuesta($id_tramite, ('nombre_arrendatario'));
                    $apellido_1 = $this->getRespuesta($id_tramite, ('apellido_1_arrendatario'));
                    $apellido_2 = $this->getRespuesta($id_tramite, ('apellido_2_arrendatario'));
                }
                break;
        }
        $nombre_formado = ($nombre_persona == false ? ' ' : $nombre_persona) . ' ' .
            ($apellido_1 == false ? ' ' : $apellido_1) . ' ' .
            ($apellido_2 == false ? ' ' : $apellido_2);
        if ($tramiteData[0]->nombre_solicitante_oficial == '') {
            Tramite::where('folio', $folio)->update(['nombre_solicitante_oficial' => $nombre_formado]);
        }
        return $nombre_formado;
    }


    public function getDataDueno($folio)
    {
        $tramiteData =  Tramite::where('folio', $folio)->get();
        $consultaData =  ConsultaRequisito::where('folio', $folio)->get();
        if (empty($consultaData)) {
            return false;
        }
        $caracter = $consultaData[0]->caracter_solicitante;
        $tipo_persona = $consultaData[0]->tipo_persona;
        $id_tramite = $consultaData[0]->id;
        $nombre_persona = '';
        $apellido_1 = '';
        $apellido_2 = '';
        $curp = '';
        switch ($caracter) {
            case 'Carta poder':
                if ($tipo_persona == 'Moral') {
                    $nombre_persona = $this->getRespuesta($id_tramite, ('razon_social_dueno_moral'));
                    $curp = $this->getRespuesta($id_tramite, ('rfc_moral_dueno_moral'));
                } else {
                    $nombre_persona = $this->getRespuesta($id_tramite, ('nombre_dueno_negocio'));
                    $apellido_1 = $this->getRespuesta($id_tramite, ('apellido_1_dueno_negocio'));
                    $apellido_2 = $this->getRespuesta($id_tramite, ('apellido_2_dueno_negocio'));
                    $curp = $this->getRespuesta($id_tramite, ('curp_dueno_negocio'));
                }
                break;
            case 'Propietario':
                if ($tipo_persona == 'Moral') {
                    $nombre_persona = $this->getRespuesta($id_tramite, ('razon_social'));
                    $curp = $this->getRespuesta($id_tramite, ('rfc_moral'));
                } else {
                    $nombre_persona = $this->getRespuesta($id_tramite, ('nombre_propietario'));
                    $apellido_1 = $this->getRespuesta($id_tramite, ('apellido_1_propietario'));
                    $apellido_2 = $this->getRespuesta($id_tramite, ('apellido_2_propietario'));
                    $curp = $this->getRespuesta($id_tramite, ('curp_propietario'));
                }
                break;
            case 'Arrendatario':
                if ($tipo_persona == 'Moral') {
                    $nombre_persona = $this->getRespuesta($id_tramite, ('razon_social'));
                    $curp = $this->getRespuesta($id_tramite, ('rfc_moral'));
                } else {
                    $nombre_persona = $this->getRespuesta($id_tramite, ('nombre_arrendatario'));
                    $apellido_1 = $this->getRespuesta($id_tramite, ('apellido_1_arrendatario'));
                    $apellido_2 = $this->getRespuesta($id_tramite, ('apellido_2_arrendatario'));
                    $curp = $this->getRespuesta($id_tramite, ('curp_arrendatario'));
                }
                break;
        }
        $nombre_formado = ($nombre_persona == false ? ' ' : $nombre_persona) . ' ' .
            ($apellido_1 == false ? ' ' : $apellido_1) . ' ' .
            ($apellido_2 == false ? ' ' : $apellido_2);
        if ($tramiteData[0]->nombre_solicitante_oficial == '') {
            Tramite::where('folio', $folio)->update(['nombre_solicitante_oficial' => $nombre_formado]);
        }
        return array('nombre' => $nombre_persona,'apellido_p'=>$apellido_1,'apellido_m'=>$apellido_2,'curp'=>$curp,'caracter'=>$tipo_persona);
    }

    public function getDataDuenoRefrendo($folio)
    {
        $tramiteData =  Tramite::where('id_consulta_requisitos', $folio)->get();
        $consultaData =  ConsultaRequisito::where('id', $folio)->get();

        if (empty($consultaData)) {
            return false;
        }
        $caracter = $consultaData[0]->caracter_solicitante;
        $tipo_persona = $consultaData[0]->tipo_persona;
        $id_tramite = $consultaData[0]->id;
        $nombre_persona = '';
        $apellido_1 = '';
        $apellido_2 = '';
        $curp = '';
        switch ($caracter) {
            case 'Carta poder':
                if ($tipo_persona == 'Moral') {
                    $nombre_persona = $this->getRespuesta($id_tramite, ('razon_social_dueno_moral'));
                    $curp = $this->getRespuesta($id_tramite, ('rfc_moral_dueno_moral'));
                } else {
                    $nombre_persona = $this->getRespuesta($id_tramite, ('nombre_dueno_negocio'));
                    $apellido_1 = $this->getRespuesta($id_tramite, ('apellido_1_dueno_negocio'));
                    $apellido_2 = $this->getRespuesta($id_tramite, ('apellido_2_dueno_negocio'));
                    $curp = $this->getRespuesta($id_tramite, ('curp_dueno_negocio'));
                }
                break;
            case 'Propietario':
                if ($tipo_persona == 'Moral') {
                    $nombre_persona = $this->getRespuesta($id_tramite, ('razon_social'));
                    $curp = $this->getRespuesta($id_tramite, ('rfc_moral'));
                } else {
                    $nombre_persona = $this->getRespuesta($id_tramite, ('nombre_propietario'));
                    $apellido_1 = $this->getRespuesta($id_tramite, ('apellido_1_propietario'));
                    $apellido_2 = $this->getRespuesta($id_tramite, ('apellido_2_propietario'));
                    $curp = $this->getRespuesta($id_tramite, ('curp_propietario'));
                }
                break;
            case 'Arrendatario':
                if ($tipo_persona == 'Moral') {
                    $nombre_persona = $this->getRespuesta($id_tramite, ('razon_social'));
                    $curp = $this->getRespuesta($id_tramite, ('rfc_moral'));
                } else {
                    $nombre_persona = $this->getRespuesta($id_tramite, ('nombre_arrendatario'));
                    $apellido_1 = $this->getRespuesta($id_tramite, ('apellido_1_arrendatario'));
                    $apellido_2 = $this->getRespuesta($id_tramite, ('apellido_2_arrendatario'));
                    $curp = $this->getRespuesta($id_tramite, ('curp_arrendatario'));
                }
                break;
        }
        $nombre_formado = ($nombre_persona == false ? ' ' : $nombre_persona) . ' ' .
            ($apellido_1 == false ? ' ' : $apellido_1) . ' ' .
            ($apellido_2 == false ? ' ' : $apellido_2);
        if ($tramiteData[0]->nombre_solicitante_oficial == '') {
            Tramite::where('id_consulta_requisitos', $folio)->update(['nombre_solicitante_oficial' => $nombre_formado]);
        }

        return array('nombre' => $nombre_persona,'apellido_p'=>$apellido_1,'apellido_m'=>$apellido_2,'curp'=>$curp,'caracter'=>$tipo_persona);
    }
    public function getRespuesta($id_tramite, $name = '')
    {
        $respuesta = Respuesta::where(['id_tramite' => $id_tramite, 'name' => $name])->get();

        if (empty($respuesta)) {
            return false;
        } else {
            return $respuesta[0]->value;
        }
    }
    public function store(TramiteCreateDto $store): Product
    {
        $entry              = new Product();
        $entry->sku         = $store->sku;
        $entry->name        = $store->name;
        $entry->description = $store->description;
        $entry->price       = $store->price;
        $entry->save();

        return $entry;
    }

    public function update(TramiteUpdateDto $store): void
    {
        $entry = Product::find($store->id);

        $entry->sku         = $store->sku;
        $entry->name        = $store->name;
        $entry->description = $store->description;
        $entry->price       = $store->price;

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

    public function copyTramite(string $folio, int $id_municipio){
        $entry = Tramite::where('folio',$folio)->get();
        $contador = DB::table('consulta_requisitos')
        ->select(DB::raw('count(*) as c'))
        ->where('id_municipio', $id_municipio)
        ->where('anio_folio', date('Y'))
        ->get();
        $contador = $contador[0]->c + 1;

        $consultaRequisitos = ConsultaRequisito::where('folio',$folio)->first();;

        $newConsultaRequisitos = $consultaRequisitos->replicate();
        $newConsultaRequisitos->created_at = Carbon::now();
        $newConsultaRequisitos->folio = null;
        $newConsultaRequisitos->folio_primary =  $consultaRequisitos->folio;
        $newConsultaRequisitos->save();

        $tramite = Tramite::where('folio',$folio)->first();

        $newTramite = $tramite->replicate();
        $newTramite->created_at               = Carbon::now();
        $newTramite->folio                    = null;
        $newTramite->folio_refrendado         = null;
        $newTramite->id_consulta_requisitos   = $newConsultaRequisitos->id;;
        $newTramite->save();

        $refrendo                         = new Refrendo();
        $refrendo->created_at             = Carbon::now();
        $refrendo->id_consulta_requisitos =  $newConsultaRequisitos->id;
        $refrendo->id_tramite             = $newTramite->id;
        $refrendo->save();

        $respuesta = Respuesta::where('id_tramite',$consultaRequisitos->id)->get();
        foreach($respuesta as $option){
                $option->id_tramite = $newConsultaRequisitos->id;
                $option->replicate()->save();
        }
        return   $refrendo->id;
    }
    public function copyTramiteHist(string $id){

        $respuesta = HistoricoLicencias::where('id',$id)->get();
        $giros = Giro::where('codigo',$respuesta[0]->codigo_giro)->get();
        if($giros->isEmpty()){
            $respuesta[0]->SCIAN =null;
        }else{
            $respuesta[0]->SCIAN = $giros[0]->SCIAN;
        }


        return   $respuesta;
    }
}
