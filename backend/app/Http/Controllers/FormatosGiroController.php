<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\Notificacion;
use App\Models\AperturaProvisional;
use App\Models\FichaTecnica;
use App\Models\FichaTecnicaDescarga;
use App\Models\FirmaMunicipio;
use App\Models\HistoricoLicencia;
use App\Models\LicenciaGiroVisor;
use App\Models\Municipio;
use App\Models\NotificacionUser;
use App\Models\Refrendo;
use App\Models\Tramite;
use App\Repositories\Interfaces\ITramiteRepository;
use App\Traits\ApiResponser;
use App\Traits\LogHistorico;
use App\Traits\LogoMunicipio;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class FormatosGiroController extends Controller
{

    private ITramiteRepository $tramiteRepository;
    use LogoMunicipio;
    use ApiResponser;
    use LogHistorico;

    public function __construct(ITramiteRepository $tramiteRepository)
    {
        $this->tramiteRepository = $tramiteRepository;
    }

    public function generarLicencia($folio, Request $request)
    {

        $data = $request->all();
        $tipo_licencia = "Nueva";
        $status_licencia = "Vigente";
        // return $data;
        $logo = $this->getImage($folio);
        $folio = base64_decode($folio);
        $duenoData = $this->tramiteRepository->getDataDueno($folio);
        $result = DB::table('consulta_requisitos')
            ->select('*')
            ->where('folio', $folio)
            ->first();
        $idConsulta = $result->id;
        $curp_proprietario = '';

        $muni = DB::table('municipios')
            ->where('id', $result->id_municipio)
            ->first();

        $firma = $muni->firma_director == null ? '' : $muni->firma_director;
        $lic = LicenciaGiroVisor::where(['folio' => $folio, 'anio_licencia' => date('Y')])->get();
        $firmas = array();
        if (count($lic) == 0) {
            $firmas = FirmaMunicipio::where(['id_municipio' => $result->id_municipio])->orderBy('orden', 'asc')->get();
            foreach ($firmas as $value) {
                $firmasF[$value->orden] = $value;
            }
            $currentUser = Auth::user();
            $cout_lics = LicenciaGiroVisor::where('municipio_id', $result->id_municipio)->max('numero_lic');

            if ($muni->folio_init > $cout_lics) {

                $cout_lics = $muni->folio_init;
            } else {
                $cout_lics = $cout_lics + 1;
            }
            $firmas_temp = $data['firmas'];
            $firmas = []; // Initialize $firmas as an array

            foreach ($firmas_temp as $value) {
                $firma2 = FirmaMunicipio::where('id', $value)->first();
                array_push($firmas, $firma2);
            }

            $firmas_json = json_encode($firmas);
            $lic = LicenciaGiroVisor::create([
                'folio' => $folio,
                'actividad_comercial' => $result->nombre_scian,
                'codigo_scian' => $result->codigo_scian,
                'superficie_autorizada' => $data['superficie'],
                'dueno' => $duenoData['nombre'],
                'apellido_p' => $duenoData['apellido_p'],
                'apellido_m' => $duenoData['apellido_m'],
                'caracter' => $duenoData['caracter'],
                'curp' => $duenoData['curp'],
                'hora_a' => $data['hora_a'],
                'hora_c' => $data['hora_c'],
                'observaciones' => $data['observaciones'],
                'costo_licencia' => $data['costo_licencia'],
                'img_logo' => $logo,
                'firma' => $firma,
                'url_minimapa' => $result->url_minimapa,
                'anio_licencia' => date('Y'),
                'id_usuario_genero' => $currentUser->id,
                'nombre_firmante_1' => isset($firmasF[1]) ? $firmasF[1]->nombre_firmante : null,
                'dependencia_1' => isset($firmasF[1]) ? $firmasF[1]->dependencia : null,
                'firma_1' => isset($firmasF[1]) ? $firmasF[1]->firma : null,
                'nombre_firmante_2' => isset($firmasF[2]) ? $firmasF[2]->nombre_firmante : null,
                'dependencia_2' => isset($firmasF[2]) ? $firmasF[2]->dependencia : null,
                'firma_2' => isset($firmasF[2]) ? $firmasF[2]->firma : null,
                'nombre_firmante_3' => isset($firmasF[3]) ? $firmasF[3]->nombre_firmante : null,
                'dependencia_3' => isset($firmasF[3]) ? $firmasF[3]->dependencia : null,
                'firma_3' => isset($firmasF[3]) ? $firmasF[3]->firma : null,
                'nombre_firmante_4' => isset($firmasF[4]) ? $firmasF[4]->nombre_firmante : null,
                'dependencia_4' => isset($firmasF[4]) ? $firmasF[4]->dependencia : null,
                'firma_4' => isset($firmasF[4]) ? $firmasF[4]->firma : null,
                'municipio_id' => $result->id_municipio,
                'numero_lic' => $cout_lics,
                'tipo_licencia' => $tipo_licencia,
                'status_licencia' => $status_licencia,
                'firmantes' =>  $firmas_json ,
                'color_licencia' => isset($muni->color_hex) ? $muni->color_hex: null
            ]);
        }
        if ($lic) {

            $t = Tramite::where('folio', $folio)->update(
                ['pdf_licencia' => "licenciaGiro/" . base64_encode($folio) . "/" . base64_encode(date('Y'))]
            );
            $tramiteSolv = Tramite::where('folio', $folio)->first();
            $this->notifiacionEmail($folio);
            $email = $this->tramiteRepository->getNombreSolicitante($folio)['email'];
            $userE = User::where(['email' => $email])->first();
            $userNotificacion = $userE->id ?? 0;
            if ($tramiteSolv->id_usuario != 0 && $tramiteSolv->id_usuario_ventanilla == 0) {
                $userNotificacion = $tramiteSolv->id_usuario;
                $email = User::find($tramiteSolv->id_usuario)->id;
            }
            $urlLic = env('APP_URL') . "licenciaGiro/" . base64_encode($folio) . "/" . base64_encode(date('Y'));
            $noti = new NotificacionUser;
            $noti->id_usuario = $userNotificacion;
            $noti->folio = $folio;
            // $noti->id_tramite = 0;
            $noti->email_solicitante = $email;
            $noti->comentario = 'Licencia emitida!!';
            $noti->fecha_creacion = Carbon::now();
            $noti->archivo_dependencia = $urlLic;
            $noti->notificado = 0;
            $noti->type = 1;
            $noti->dependencia_notifica = 4;
            $noti->save();
            //historico
            $data2 = json_encode($request->except(['token']));
            $valor_anterior = $folio;
            //emision de licencias
            $this->historial($accion = 'Se emitio una licencia', $valor_anterior, $data2, $tipo = 5, 0);
            return $this->successResponse($urlLic);
        }
        return $this->errorResponse('No tienes permiso', 403);
    }
    public function generarLicenciaRefrendo($folio, Request $request)
    {
        $anio_licencia = date('Y');
        $data = $request->all();
        $tipo_licencia = "Refrendo";
        $status_licencia = "Vigente";
        // return $data;
        $folio = base64_decode($folio);

        $RefrendoTramite = Refrendo::where('id', $folio)->firstOrFail();
        $id_consulta_requisitos = $RefrendoTramite->id_consulta_requisitos;

        $logo = $this->getImageRefrendo($id_consulta_requisitos);

        $duenoData = $this->tramiteRepository->getDataDuenoRefrendo($id_consulta_requisitos);

        $result = DB::table('consulta_requisitos')
            ->select('*')
            ->where('id', $id_consulta_requisitos)
            ->first();
        $idConsulta = $result->id;
        $curp_proprietario = '';

        $dataRequi = DB::table('consulta_requisitos')
            ->select('*')
            ->where('folio', $result->folio_primary)
            ->orderBy('id', 'asc')
            ->first();
        $muni = DB::table('municipios')
            ->where('id', $result->id_municipio)
            ->first();

        $folio_padre = $result->folio_primary;

        $firma = $muni->firma_director == null ? '' : $muni->firma_director;
        $lic = LicenciaGiroVisor::where(['folio' => $folio, 'anio_licencia' => date('Y')])->get();
        $lic2 = LicenciaGiroVisor::where(['folio' => $folio_padre])->first();

        /*********************************NUEVO PROCESO**********************************/
        $firmas_temp = $data['firmas'];
        $firmas = []; // Initialize $firmas as an array

        foreach ($firmas_temp as $value) {
            $firma2 = FirmaMunicipio::where('id', $value)->first();
            array_push($firmas, $firma2);
        }
        $firmas_json = json_encode($firmas);
        /*********************************NUEVO PROCESO END**********************************/

        if (count($lic) == 0) {

            $firmas = FirmaMunicipio::where(['id_municipio' => $result->id_municipio])->orderBy('orden', 'asc')->get();

            foreach ($firmas as $value) {
                $firmasF[$value->orden] = $value;
            }
            $currentUser = Auth::user();
            $cout_lics = LicenciaGiroVisor::where(['municipio_id' => $result->id_municipio])->count();
            $anio = $data['anio'] ? $data['anio'] : date('Y');
            $lic = LicenciaGiroVisor::create([
                'folio' => $folio_padre,
                'actividad_comercial' => $result->nombre_scian,
                'codigo_scian' => $result->codigo_scian,
                'superficie_autorizada' => $data['superficie'],
                'dueno' => $duenoData['nombre'],
                'apellido_p' => $duenoData['apellido_p'],
                'apellido_m' => $duenoData['apellido_m'],
                'caracter' => $duenoData['caracter'],
                'curp' => $duenoData['curp'],
                'hora_a' => $data['hora_a'],
                'hora_c' => $data['hora_c'],
                'costo_licencia' => $data['costo_licencia'],
                'img_logo' => $logo,
                'firma' => $firma,
                'url_minimapa' => $result->url_minimapa,
                'anio_licencia' => $data['anio'] ? $data['anio'] : date('Y'),
                'id_usuario_genero' => $currentUser->id,
                'nombre_firmante_1' => isset($firmasF[1]) ? $firmasF[1]->nombre_firmante : null,
                'dependencia_1' => isset($firmasF[1]) ? $firmasF[1]->dependencia : null,
                'firma_1' => isset($firmasF[1]) ? $firmasF[1]->firma : null,
                'nombre_firmante_2' => isset($firmasF[2]) ? $firmasF[2]->nombre_firmante : null,
                'dependencia_2' => isset($firmasF[2]) ? $firmasF[2]->dependencia : null,
                'firma_2' => isset($firmasF[2]) ? $firmasF[2]->firma : null,
                'nombre_firmante_3' => isset($firmasF[3]) ? $firmasF[3]->nombre_firmante : null,
                'dependencia_3' => isset($firmasF[3]) ? $firmasF[3]->dependencia : null,
                'firma_3' => isset($firmasF[3]) ? $firmasF[3]->firma : null,
                'nombre_firmante_4' => isset($firmasF[4]) ? $firmasF[4]->nombre_firmante : null,
                'dependencia_4' => isset($firmasF[4]) ? $firmasF[4]->dependencia : null,
                'firma_4' => isset($firmasF[4]) ? $firmasF[4]->firma : null,
                'municipio_id' => $result->id_municipio,
                'numero_lic' => $lic2['numero_lic'],
                'tipo_licencia' => $tipo_licencia,
                'status_licencia' => $status_licencia,
                'firmantes' =>  $firmas_json ,
                'color_licencia' => isset($muni->color_hex) ? $muni->color_hex: null
            ]);
        }

        if ($lic) {
            // return $final;
            $t = Tramite::where('id_consulta_requisitos', $id_consulta_requisitos)->update(
                ['pdf_licencia' => "licenciaGiro/" . base64_encode($folio_padre) . "/" . base64_encode(date('Y'))]
            );
            $tramiteSolv = Tramite::where('id_consulta_requisitos', $id_consulta_requisitos)->first();
            $this->notifiacionEmailRefrendo($id_consulta_requisitos);
            $email = $this->tramiteRepository->getNombreSolicitanteRefrendo($id_consulta_requisitos)['email'];
            $userE = User::where(['email' => $email])->first();
            $userNotificacion = $userE->id ?? 0;
            if ($tramiteSolv->id_usuario != 0 && $tramiteSolv->id_usuario_ventanilla == 0) {
                $userNotificacion = $tramiteSolv->id_usuario;
                $email = User::find($tramiteSolv->id_usuario)->id;
            }
            //$urlLic = env('APP_URL') . "licenciaGiro/" . base64_encode($folio_padre) . "/" . base64_encode($anio);
            // 'licenciaGiros/{folio}/{anio}/{numero}/{id}/{idLic}'
            //dd($folio, $anio, $tip, $id, $idLic);
            //dd($folio_padre, $anio, 2, $dataRequi->id, $lic->id);

            $urlLic = env('APP_URL') . "licenciaGiros/" . base64_encode($folio_padre) . "/" . base64_encode($anio)."/". base64_encode(2)."/". base64_encode($dataRequi->id)."/". base64_encode($lic->id);
            $noti = new NotificacionUser;
            $noti->id_usuario = $userNotificacion;
            $noti->folio = $folio_padre;
            // $noti->id_tramite = 0;
            $noti->email_solicitante = $email;
            $noti->comentario = 'Refrendo emitido!';
            $noti->fecha_creacion = Carbon::now();
            //$noti->archivo_dependencia = $urlLic . "/" . base64_encode(2);
            $noti->archivo_dependencia = $urlLic;
            $noti->notificado = 0;
            $noti->type = 1;
            $noti->dependencia_notifica = 4;
            $noti->save();
            //historico
            $data2 = json_encode($request->except(['token']));
            $valor_anterior = $folio_padre;
            //emision de licencias
            $this->historial($accion = 'Se emitio un refrendo', $valor_anterior, $data2, $tipo = 5, 0);

            return $this->successResponse($urlLic);

        }
        return $this->errorResponse('No tienes permiso', 403);
    }

    public function generarLicenciaRefrendoHistorico($folio, Request $request)
    {

        $data = $request->all();
        $tipo_licencia = "Refrendo";
        $status_licencia = "Vigente";

        /*********************************NUEVO PROCESO**********************************/
        $firmas_temp = $data['firmas'];
        $firmas = []; // Initialize $firmas as an array

        foreach ($firmas_temp as $value) {
            $firma2 = FirmaMunicipio::where('id', $value)->first();
            array_push($firmas, $firma2);
        }
        $firmas_json = json_encode($firmas);
        /*********************************NUEVO PROCESO END**********************************/

        $folio = $data['folio'];
        $historico = HistoricoLicencia::find($folio);
        $historico->status_licencia = $status_licencia;
        $historico->tipo_licencia = $tipo_licencia;
        $historico->hora_c = $data['hora_c'];
        $historico->hora_a = $data['hora_a'];
        $historico->anio_licencia = $data['anio'];
        $historico->superficie_giro = $data['superficie'];
        $historico->status_baja = 0;
        $historico->status_pago = 0;
        $historico->status = 1;
        $historico->fecha_emision = date("d/m/Y");
        $historico->firmantes = $firmas_json;
        $historico->save();
        $RefrendoTramite = HistoricoLicencia::where('id', $folio)->firstOrFail();

        $muni = DB::table('municipios')
            ->where('id', $RefrendoTramite->id_municipio)
            ->first();
        $folio_padre = $RefrendoTramite->folio_licencia;
        $firma = $muni->firma_director == null ? '' : $muni->firma_director;
        $userNotificacion = $request->user_id ?? 0;
        /*
        $tramiteSolv = Tramite::where('id_consulta_requisitos', $id_consulta_requisitos)->first();
        $this->notifiacionEmailRefrendo($id_consulta_requisitos);
        $email = $this->tramiteRepository->getNombreSolicitanteRefrendo($id_consulta_requisitos)['email'];
        $userE = User::where(['email' => $email])->first();

        if ($tramiteSolv->id_usuario != 0 && $tramiteSolv->id_usuario_ventanilla == 0) {
        $userNotificacion = $tramiteSolv->id_usuario;
        $email = User::find($tramiteSolv->id_usuario)->id;
        }*/
        $urlLic = env('APP_URL') . "licenciaGiroHistorico/" . base64_encode($folio) . "/" . base64_encode(date('Y')) . "/" . base64_encode(1);
        $noti = new NotificacionUser;
        $noti->id_usuario = $userNotificacion;
        $noti->folio = $folio;
        $noti->email_solicitante = $RefrendoTramite->email;
        $noti->comentario = 'Refrendo emitido!';
        $noti->fecha_creacion = Carbon::now();
        $noti->archivo_dependencia = $urlLic;
        $noti->notificado = 0;
        $noti->type = 1;
        $noti->dependencia_notifica = 4;
        $noti->save();
        //historico
        $data2 = json_encode($request->except(['token']));
        $valor_anterior = $folio_padre;
        //emision de licencias
        $this->historial($accion = 'Se emitio un refrendo', $valor_anterior, $data2, $tipo = 5, 0);
        return $this->successResponse($urlLic);
        return $this->errorResponse('No tienes permiso', 403);
    }
    public function generarLicenciaContinuar($folio, Request $request)
    {

        $data = $request->all();
        // return $data;
        $tipo_licencia = "Nueva";
        $status_licencia = "Vigente";
        $logo = $this->getImage($folio);
        $folio = base64_decode($folio);
        $data_continuar = $this->tramiteRepository->ingresoTramiteGenerarLicencia($folio);
        if (!$data_continuar['status']) {
            return $this->errorResponse($data_continuar['message'], 400);
        }
        $duenoData = $this->tramiteRepository->getDataDueno($folio);
        $result = DB::table('consulta_requisitos')
            ->select('*')
            ->where('folio', $folio)
            ->first();
        $idConsulta = $result->id;
        $curp_proprietario = '';

        $muni = DB::table('municipios')
            ->where('id', $result->id_municipio)
            ->first();
        $firma = $muni->firma_director == null ? '' : $muni->firma_director;
        $lic = LicenciaGiroVisor::where(['folio' => $folio, 'anio_licencia' => date('Y')])->get();

        /*********************************NUEVO PROCESO**********************************/
        $firmas_temp = $data['firmas'];
        $firmas = []; // Initialize $firmas as an array

        foreach ($firmas_temp as $value) {
            $firma2 = FirmaMunicipio::where('id', $value)->first();
            array_push($firmas, $firma2);
        }
        $firmas_json = json_encode($firmas);
        /*********************************NUEVO PROCESO END**********************************/

        if (count($lic) == 0) {
            $firmas = FirmaMunicipio::where(['id_municipio' => $result->id_municipio])->orderBy('orden', 'asc')->get();
            foreach ($firmas as $value) {
                $firmasF[$value->orden] = $value;
            }
            $cout_lics = LicenciaGiroVisor::where(['municipio_id' => $result->id_municipio])->count();
            $currentUser = Auth::user();
            $lic = LicenciaGiroVisor::create([
                'folio' => $folio,
                'actividad_comercial' => $result->nombre_scian,
                'codigo_scian' => $result->codigo_scian,
                'superficie_autorizada' => $data['superficie'],
                'dueno' => $duenoData['nombre'],
                'apellido_p' => $duenoData['apellido_p'],
                'apellido_m' => $duenoData['apellido_m'],
                'caracter' => $duenoData['caracter'],
                'curp' => $duenoData['curp'],
                'hora_a' => $data['hora_a'],
                'hora_c' => $data['hora_c'],
                'img_logo' => $logo,
                'firma' => $firma,
                'url_minimapa' => $result->url_minimapa,
                'anio_licencia' => date('Y'),
                'id_usuario_genero' => $currentUser->id,
                'status_pago' => 0,
                'observaciones' => $data['observaciones'],
                'tipo' => 2, //Generado
                'nombre_firmante_1' => isset($firmasF[1]) ? $firmasF[1]->nombre_firmante : null,
                'dependencia_1' => isset($firmasF[1]) ? $firmasF[1]->dependencia : null,
                'firma_1' => isset($firmasF[1]) ? $firmasF[1]->firma : null,
                'nombre_firmante_2' => isset($firmasF[2]) ? $firmasF[2]->nombre_firmante : null,
                'dependencia_2' => isset($firmasF[2]) ? $firmasF[2]->dependencia : null,
                'firma_2' => isset($firmasF[2]) ? $firmasF[2]->firma : null,
                'nombre_firmante_3' => isset($firmasF[3]) ? $firmasF[3]->nombre_firmante : null,
                'dependencia_3' => isset($firmasF[3]) ? $firmasF[3]->dependencia : null,
                'firma_3' => isset($firmasF[3]) ? $firmasF[3]->firma : null,
                'nombre_firmante_4' => isset($firmasF[4]) ? $firmasF[4]->nombre_firmante : null,
                'dependencia_4' => isset($firmasF[4]) ? $firmasF[4]->dependencia : null,
                'firma_4' => isset($firmasF[4]) ? $firmasF[4]->firma : null,
                'municipio_id' => $result->id_municipio,
                'numero_lic' => $cout_lics + 1,
                'tipo_licencia' => $tipo_licencia,
                'status_licencia' => $status_licencia,
                'firmantes' =>  $firmas_json ,
                'color_licencia' => isset($muni->color_hex) ? $muni->color_hex: null
            ]);
        }
        if ($lic) {
            // return $final;
            $t = Tramite::where('folio', $folio)->update(['aprobado_director' => 1, 'pdf_licencia' => "licenciaGiro/" . base64_encode($folio) . "/" . base64_encode(date('Y'))]);
            $tramiteSolv = Tramite::where('folio', $folio)->first();
            $this->notifiacionEmail($folio);
            $email = $this->tramiteRepository->getNombreSolicitante($folio)['email'];
            $userE = User::where(['email' => $email])->first();
            $userNotificacion = $userE->id ?? 0;
            if ($tramiteSolv->id_usuario != 0 && $tramiteSolv->id_usuario_ventanilla == 0) {
                $userNotificacion = $tramiteSolv->id_usuario;
                $email = User::find($tramiteSolv->id_usuario)->id;
            }
            $urlLic = env('APP_URL') . "licenciaGiro/" . base64_encode($folio) . "/" . base64_encode(date('Y'));
            $noti = new NotificacionUser;
            $noti->id_usuario = $userNotificacion;
            $noti->folio = $folio;
            // $noti->id_tramite = 0;
            $noti->email_solicitante = $email;
            $noti->comentario = 'Licencia emitida!!';
            $noti->fecha_creacion = Carbon::now();
            $noti->archivo_dependencia = $urlLic;
            $noti->notificado = 0;
            $noti->type = 1;
            $noti->dependencia_notifica = 4;
            $noti->save();
            return $this->successResponse($urlLic);
        }
        return $this->errorResponse('No tienes permiso', 403);
    }

    public function licenciaGiroRefrendo($folio, $anio, $numero)
    {

        $logo = $this->getImage($folio);
        $urlLic = env('APP_URL');
        $qrCode = new QrCode($urlLic . "licenciaGiro/$folio/$anio/$numero");
        $qrCode = $qrCode->writeString();
        $qrCode = base64_encode($qrCode);
        $respuesta = self::getPdfValues($folio);
        $info_anuncio= $respuesta['info_anuncio'];
        $ubicacion = $respuesta['domicilio']['calle'] . ' número ' . $respuesta['domicilio']['exterior'] .
            (isset($respuesta['domicilio']['interior']) ? $respuesta['domicilio']['interior'] : '') . ' colonia ' . $respuesta['domicilio']['colonia'];
        $folio = base64_decode($folio);
        $anio = base64_decode($anio);
        $tip = base64_decode($numero);

        if ($tip == 1) {
            $result = DB::table('consulta_requisitos')
                ->select('*')
                ->where('folio', $folio)
                ->first();
            $tipo = 'Nueva';
        } else {
            $result = DB::table('consulta_requisitos')
                ->select('*')
                ->where('folio_primary', $folio)
                ->first();
            $tipo = 'Refrendo';
        }

        $curp_proprietario = '';
        $firma = '';
        $dueno = $this->tramiteRepository->getDueno($folio);
        $firmasF = [];
        $firmas = FirmaMunicipio::where(['id_municipio' => $result->id_municipio])->orderBy('orden', 'asc')->get();

        foreach ($firmas as $value) {

            $firmasF[$value->orden] = $value;
        }

        $muniData = Municipio::find($result->id_municipio);
        $descripcion = DB::table('respuestas')->where(['id_tramite' => $result->id, 'name' => 'descripcion_actividad'])->first();

        $mes = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $lic = LicenciaGiroVisor::where(['folio' => $folio, 'anio_licencia' => $anio, 'tipo_licencia' => $tipo])->first();
        if ($lic->pdf_escaneado != '') {
            return redirect($lic->pdf_escaneado);
        }

        $pdf = PDF::loadView('licenciaNegocio', [
            "data" => $lic, "firmas" => $firmasF, "mes" => $mes, "dueno" => $dueno, "result" => $result,
            'ubicacion' => $ubicacion, "data_muni" => $muniData, "curp" => $curp_proprietario, "qrCode" => $qrCode, "logo" => $logo, "desc" => $descripcion, "info_anuncio" => $info_anuncio,
        ])
            ->setWarnings(false);
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOptions(
            [
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]
        );
        //$pdf->render();
        return $pdf->stream("Licencia de negocio.pdf");
    }
    public function licenciaGiroRef($folio, $anio, $numero, $id, $idLic=null)
    {
	//return $idLic;
        $logo = $this->getImage($folio);
        $urlLic = env('APP_URL');
        $qrCode = new QrCode($urlLic . "licenciaGiro/$folio/$anio/$numero/$id");
        $qrCode = $qrCode->writeString();
        $qrCode = base64_encode($qrCode);
        $respuesta = self::getPdfValues($folio);
        $info_anuncio= $respuesta['info_anuncio'];
        $ubicacion = $respuesta['domicilio']['calle'] . ' número ' . $respuesta['domicilio']['exterior'] .
            (isset($respuesta['domicilio']['interior']) ? $respuesta['domicilio']['interior'] : '') . ' colonia ' . $respuesta['domicilio']['colonia'];
        $folio = base64_decode($folio);
        $anio = base64_decode($anio);
        $tip = base64_decode($numero);
        $id = base64_decode($id);
        $idLic = base64_decode($idLic);
        //dd($folio, $anio, $numero, $id, $idLic);
        if ($tip == 1) {
            $result = DB::table('consulta_requisitos')
                ->select('*')
                ->where('id', $id)
                ->first();
            $tipo = 'Nueva';
        } else {
            $result = DB::table('consulta_requisitos')
                ->select('*')
                ->where('id', $id)
                ->first();
            $tipo = 'Refrendo';
        }

        $curp_proprietario = '';
        $firma = '';
        $dueno = $this->tramiteRepository->getDueno($folio);
        $firmasF = [];
        $firmas = FirmaMunicipio::where(['id_municipio' => $result->id_municipio])->orderBy('orden', 'asc')->get();

        foreach ($firmas as $value) {

            $firmasF[$value->orden] = $value;
        }

        $muniData = Municipio::find($result->id_municipio);
        $descripcion = DB::table('respuestas')->where(['id_tramite' => $result->id, 'name' => 'descripcion_actividad'])->first();
	if($idLic == null){
		$idLic = $id;
	}
        $mes = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $lic = LicenciaGiroVisor::where(['id' => $idLic, 'folio' => $folio, 'anio_licencia' => $anio, 'tipo_licencia' => $tipo])->orderBy('id', 'desc')->first();

        if ($lic->pdf_escaneado != '') {
            return redirect($lic->pdf_escaneado);
        }

        $pdf = PDF::loadView('licenciaNegocio', [
            "data" => $lic, "firmas" => $firmasF, "mes" => $mes, "dueno" => $dueno, "result" => $result,
            'ubicacion' => $ubicacion, "data_muni" => $muniData, "curp" => $curp_proprietario, "qrCode" => $qrCode, "logo" => $logo, "desc" => $descripcion, "info_anuncio" => $info_anuncio,
        ])
            ->setWarnings(false);
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOptions(
            [
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]
        );
        //$pdf->render();
        return $pdf->stream("Licencia de negocio.pdf");
    }
    public function licenciaGiro($folio, $anio)
    {
        $logo = $this->getImage($folio);
        $urlLic = env('APP_URL');
        $qrCode = new QrCode($urlLic . "licenciaGiro/$folio/$anio");
        $qrCode = $qrCode->writeString();
        $qrCode = base64_encode($qrCode);
        $respuesta = self::getPdfValues($folio);
        $info_anuncio= $respuesta['info_anuncio'];
        $ubicacion = $respuesta['domicilio']['calle'] . ' número ' . $respuesta['domicilio']['exterior'] .
            (isset($respuesta['domicilio']['interior']) ? $respuesta['domicilio']['interior'] : '') . ' colonia ' . $respuesta['domicilio']['colonia'];
        $folio = base64_decode($folio);
        $anio = base64_decode($anio);
        $result = DB::table('consulta_requisitos')
            ->select('*')
            ->where('folio', $folio)
            ->first();
        $tipo = 'Nueva';
        $curp_proprietario = '';
        $firma = '';
        $dueno = $this->tramiteRepository->getDueno($folio);
        $firmasF = [];
        $firmas = FirmaMunicipio::where(['id_municipio' => $result->id_municipio])->orderBy('orden', 'asc')->get();
        foreach ($firmas as $value) {

            $firmasF[$value->orden] = $value;
        }
        $muniData = Municipio::find($result->id_municipio);
        $descripcion = DB::table('respuestas')->where(['id_tramite' => $result->id, 'name' => 'descripcion_actividad'])->first();

        $mes = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $lic = LicenciaGiroVisor::where(['folio' => $folio, 'anio_licencia' => $anio, 'tipo_licencia' => $tipo])->first();
        if ($lic->pdf_escaneado != '') {
            return redirect($lic->pdf_escaneado);
        }

        $pdf = PDF::loadView('licenciaNegocio', [
            "data" => $lic, "firmas" => $firmasF, "mes" => $mes, "dueno" => $dueno, "result" => $result,
            'ubicacion' => $ubicacion, "data_muni" => $muniData, "curp" => $curp_proprietario, "qrCode" => $qrCode, "logo" => $logo, "desc" => $descripcion, "info_anuncio" => $info_anuncio
        ])->setWarnings(false);
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOptions(
            [
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]
        );
        //$pdf->render();
        return $pdf->stream("Licencia de negocio.pdf");
    }
    public function licenciaGiroTest($id_municipio)
    {
        //$folio= '1-4/2021';
        $folio = '1-1/2024';
        $anio= '2024';
        $logo = $this->getImageByID($id_municipio);
        $urlLic = env('APP_URL');
        $qrCode = new QrCode($urlLic . "licenciaGiro/$folio/$anio");
        $qrCode = $qrCode->writeString();
        $qrCode = base64_encode($qrCode);
        //$respuesta = self::getPdfValues($folio);
              $folio =$folio;
        $anio = base64_decode($anio);
        $result = DB::table('consulta_requisitos')
            ->select('*')
            ->where('folio', $folio)
            ->first();
        $tipo = 'Nueva';

        $curp_proprietario = '';
        $firma = '';
        //$dueno = $this->tramiteRepository->getDueno($folio);
        $firmasF = [];
        $firmas = FirmaMunicipio::where(['id_municipio' => $id_municipio])->orderBy('orden', 'asc')->get();
        $imgMapa = $urlLic = env('APP_URL') . "images/img-mapa.png";
        $imgCuernavaca = $urlLic = env('APP_URL') . "assets/img/logo_cuernavaca.png";
;
        foreach ($firmas as $value) {

            $firmasF[$value->orden] = $value;
        }

        $muniData = Municipio::find($id_municipio);
        $mes = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $lic = LicenciaGiroVisor::where(['folio' => $folio])->first();
        $logos = [
            "logo_cuernavaca" => $imgCuernavaca,
            "logo_mapa" => $imgMapa,
        ];
        //dd($logos);

        $pdf = PDF::loadView('licenciaNegocioTest', [
            "data" => $lic, "firmas" => $firmasF, "mes" => $mes, "result" => $result,
            'ubicacion' => '', "data_muni" => $muniData, "curp" => $curp_proprietario, "qrCode" => $qrCode, "logo" => $logo, "info_anuncio" => '',
            "logos" => $logos,
        ])->setWarnings(false);

        $pdf->setPaper('letter', 'portrait');
        $pdf->setOptions(
            [
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]
        );
        return $pdf->stream("Licencia de negocio.pdf");
    }


    public function licenciaGiroHistorico($folio, $anio, $numero)
    {;
        $logo = $this->getImageHistorico($folio);
        $urlLic = env('APP_URL');
        $qrCode = new QrCode($urlLic . "licenciaGiroHistorico/$folio/$anio/$numero");
        $qrCode = $qrCode->writeString();
        $qrCode = base64_encode($qrCode);
        $respuesta = self::getPdfValuesHistorico($folio);
        $ubicacion = $respuesta['domicilio']['calle'] . ' no. ' . $respuesta['domicilio']['exterior'] . ' ' .
            (isset($respuesta['domicilio']['interior']) || $respuesta['domicilio']['interior'] != 'null' ? ' int. ' . $respuesta['domicilio']['interior'] : ' ') . ' colonia ' . $respuesta['domicilio']['colonia'];
        $folio = base64_decode($folio);
        $anio = base64_decode($anio);
        $tip = base64_decode($numero);
        $result = DB::table('historico_licencias_giro')
            ->select('*')
            ->where('id', $folio)
            ->first();
        if ($tip == 1) {
            $tipo = 'Nueva';
        } else {
            $tipo = 'Refrendo';
        }

        $firmasF = [];
        $firmasJsonArray = [];


        $firmasJson = json_decode($result->firmantes);
        foreach ($firmasJson as $value) {
            $firmasJsonArray[$value->orden] = $value;
        }


        $firmasF = [];
        $firmas = FirmaMunicipio::where(['id_municipio' => $result->id_municipio])->orderBy('orden', 'asc')->get();
        foreach ($firmas as $value) {
            $firmasF[$value->orden] = $value;
        }

        if ($result->razon_social) {
            $duenos = $result->razon_social;
        } else {
            $duenos = $result->nombre_titular . ' ' . $result->apellido_p . ' ' . $result->apellido_m;
        }

        $bboxUrl = env("GEOSERVER_APP") . "ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:licencias&outputFormat=application/json&cql_filter=id_historico=" . $folio;
        $responseBB = $this->curlUrl($bboxUrl);
        $responseBB = json_decode($responseBB, true);
        $img_mapa = "";
        // return var_dump($responseBB);

        if (isset($responseBB['numberReturned']) && $responseBB['numberReturned'] > 0) {

            $bb = $responseBB['features'][0];
            $bb = $bb['properties']['bbox'];
            $img_mapa = env("GEOSERVER_APP") . "ows?service=WMS&version=1.3.0&request=GetMap&FORMAT=image/png8&layers=VUJ:MapaJaliscoMapaBase,VUJ:catastro,VUJ:registrotramite&exceptions=application/vnd.ogc.se_inimage&CRS=EPSG:4326&width=600&height=600&styles=,,VUJ:Predio&cql_filter=INCLUDE;INCLUDE;id_historico=" . $folio . "&BBOX=" . $bb;
        }
        //return $img_mapa;
        $muniData = Municipio::find($result->id_municipio);
        $mes = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $pdf = PDF::loadView('licenciaNegocioHistorico', [
            "data" => $result,
            "firmas" => $firmasF,
            "firmasJson" => $firmasJsonArray,
            "mes" => $mes,
            "dueno" => $duenos,
            "result" => $result,
            'ubicacion' => $ubicacion,
            "data_muni" => $muniData,
            "curp" => $result->curp,
            "qrCode" => $qrCode,
            "logo" => $logo,
            "desc" => $result->descripcion_detallada,
            "img_mapa" => $img_mapa,
        ])
            ->setWarnings(false);
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOptions(
            [
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]
        );
        //$pdf->render();
        return $pdf->stream("Licencia de negocio.pdf");
    }

    public function aperturaProvisional($folio)
    {

        $logo = $this->getImage($folio);
        $qrCode = new QrCode("https://api.visorurbano.cuernavaca.gob.mx/aperturaProvisional/$folio");
        $qrCode = $qrCode->writeString();
        $qrCode = base64_encode($qrCode);
        $respuesta = self::getPdfValues($folio);
        $ubicacion = $respuesta['domicilio']['calle'] . ' número ' . $respuesta['domicilio']['exterior'] . ' ' . (isset($respuesta['domicilio']['interior']) ? $respuesta['domicilio']['interior'] : '') . ' colonia ' . $respuesta['domicilio']['colonia'];

        $folio = base64_decode($folio);
        $result = DB::table('consulta_requisitos')
            ->select('*')
            ->where('folio', $folio)
            ->first();
        $idConsulta = $result->id;
        $curp_proprietario = '';
        $firma = '';
        // $curp_proprietario = $this->tramiteRepository->getRespuesta($idConsulta,  'curp_propietario');
        // $curp_proprietario = strtoupper($curp_proprietario);
        $dueno = $this->tramiteRepository->getDueno($folio);

        //  return $duenoData;
        $data = array(
            "folio" => $folio,
            "id_tramite" => $result->id,
            "contador" => 1,
            "id_usuario_otorgo" => 1,
            "rol_otorgo" => 1,
            "fecha_inicio" => Carbon::now(),
            "fecha_limite" => Carbon::now()->addDays(30),
            "status" => 1,
        );

        //AperturaProvisional::insert($data);
        $apertura = AperturaProvisional::where(['folio' => $folio, 'contador' => 1])->get();
        if (count($apertura) == 0) {
            return "No existe la ";
        }

        $pdf = PDF::loadView('aperturaProvisional', [
            "data" => $apertura[0], "dueno" => $dueno, "result" => $result,
            'ubicacion' => $ubicacion, "curp" => $curp_proprietario, "qrCode" => $qrCode, "logo" => $logo,
        ])
            ->setWarnings(false);
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOptions(
            [
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]
        );
        //$pdf->render();
        return $pdf->stream('Apertura Provisional.pdf');
    }

    public function acuseNoFirmado($folio)
    {

        $logo = $this->getImage($folio);
        $qrCode = new QrCode("https://api.visorurbano.cuernavaca.gob.mx/acuseNoFirmado/$folio");
        $qrCode = $qrCode->writeString();
        $qrCode = base64_encode($qrCode);
        $dataCarta = self::getPdfValues($folio);

        $pdf = PDF::loadView('recepcionDocumentos', ["data" => $dataCarta, "qrCode" => $qrCode, "logo" => $logo])->setWarnings(false);
        $pdf->setOptions(
            [
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]
        );

        return $pdf->stream('Acuse - NoFirmadaElectronica y Giro Permitido.pdf');
    }

    public function getPdfValues($folio)
    {

        $folio = base64_decode($folio);
        $result = DB::table('consulta_requisitos')
            ->select('*')
            ->where('folio', $folio)
            ->get();

        $idConsulta = $result[0]->id;

        $calle_predio = $this->tramiteRepository->getRespuesta($idConsulta, 'calle_predio');
        $numero_exterior_predio = $this->tramiteRepository->getRespuesta($idConsulta, 'numero_exterior_predio');
        $numero_interior_predio = $this->tramiteRepository->getRespuesta($idConsulta, 'numero_interior_predio');
        $numero_interior_predio = $numero_interior_predio != '' ? ' int. ' . $numero_interior_predio : '';
        $colonia_predio = $this->tramiteRepository->getRespuesta($idConsulta, 'colonia_predio');
        $cp_predio = $this->tramiteRepository->getRespuesta($idConsulta, 'cp_predio');
        DB::table('consulta_requisitos')->where('folio', $folio)->update(['calle' => $calle_predio . " número " . $numero_exterior_predio . " " . $numero_interior_predio, 'colonia' => $colonia_predio]);
        $domicilio_predio = array(
            "calle" => $calle_predio,
            "exterior" => $numero_exterior_predio,
            "interior" => $numero_interior_predio,
            "colonia" => $colonia_predio,
            "cp" => $cp_predio,
        );

        $data = DB::table('campos')
        ->select('respuestas.name', 'respuestas.value', 'campos.description')
        ->join('respuestas', 'campos.name', '=', 'respuestas.name')
        ->where('id_tramite', '=', $idConsulta)
        ->get();

        $info_anuncio = DB::table('campos')
            ->select('respuestas.name', 'respuestas.value', 'campos.description')
            ->join('respuestas', 'campos.name', '=', 'respuestas.name')
            ->where('id_tramite', '=', $idConsulta)
            ->where('campos.name', 'info_anuncio')
            ->first();

        $name = $this->tramiteRepository->getNombreSolicitante($folio)['nombre'];
        $datos = array("result" => $result, "name" => $name,"data" => $data,  "info_anuncio" => $info_anuncio->value ?? null, "domicilio" => $domicilio_predio);


        return $datos;
    }

    public function getPdfValuesHistorico($folio)
    {

        $folio = base64_decode($folio);
        $result = DB::table('historico_licencias_giro')
            ->select('*')
            ->where('id', $folio)
            ->get();

        $idConsulta = $result[0]->id;

        $calle_predio = $result[0]->calle_predio;
        $numero_exterior_predio = $result[0]->num_ext_predio;
        $numero_interior_predio = $result[0]->num_int_predio;
        $colonia_predio = $result[0]->colonia_predio;
        $cp_predio = $result[0]->cp_predio;

        $domicilio_predio = array(
            "calle" => $calle_predio,
            "exterior" => $numero_exterior_predio,
            "interior" => $numero_interior_predio,
            "colonia" => $colonia_predio,
            "cp" => $cp_predio,
        );

        $name = $result[0]->nombre_solicitante . ' ' . $result[0]->apellido_solicitante_p . ' ' . $result[0]->apellido_solicitante_m;
        $datos = array("result" => $result, "name" => $name, "data" => $result, "domicilio" => $domicilio_predio);

        return $datos;
    }

    public function cartaResponsiva($folio)
    {
        $logo = $this->getImage($folio);
        $dataCarta = self::getPdfValues($folio);

        $pdf = PDF::loadView('cartaResponsiva', ["data" => $dataCarta, 'logo' => $logo])->setWarnings(false);
        $pdf->setOptions(
            [
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]
        );

        return $pdf->stream('carta Responsiva.pdf');
    }

    public function acuseFirmado($folio)
    {
        $municipio = DB::table('municipios')
            ->select('*')
            //->where('consulta_requisitos.folio', $folio)
            ->get()[0];
        $logo = $this->getImage($folio);
        $qrCode = new QrCode("https://api.visorurbano.cuernavaca.gob.mx/acuseFirmado/$folio");
        $qrCode = $qrCode->writeString();
        $qrCode = base64_encode($qrCode);

        $dataCarta = self::getPdfValues($folio);

        $pdf = PDF::loadView('recepcionEnvio', [
            "data" => $dataCarta, "qrCode" => $qrCode, 'logo' => $logo, 'municipio' => $municipio
        ])->setWarnings(false);
        $pdf->setOptions(
            [
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]
        );

        return $pdf->stream('Acuse - FirmadaElectronica y Giro Permitido.pdf');
    }

    public function acuseVentanilla($folio)
    {
        $logo = $this->getImage($folio);
        $qrCode = new QrCode("https://api.visorurbano.cuernavaca.gob.mx/acuseVentanilla/$folio");
        $qrCode = $qrCode->writeString();
        $qrCode = base64_encode($qrCode);
        $dataCarta = self::getPdfValues($folio);

        $apertura = AperturaProvisional::where(['folio' => $folio, 'contador' => 1])->get();

        $pdf = PDF::loadView('acuseVentanilla', ["data" => $dataCarta, "qrCode" => $qrCode, 'logo' => $logo, 'apertura' => $apertura])->setWarnings(false);
        $pdf->setOptions(
            [
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]
        );

        return $pdf->stream('Acuse de inicio de trámite.pdf');
    }
    public function getImageRefrendo($folio)
    {
        $data = DB::table('municipios')
            ->join('consulta_requisitos', 'consulta_requisitos.id_municipio', 'municipios.id')
            ->select('image')
            ->where('consulta_requisitos.id', $folio)
            ->get();
        $image = $data[0]->image;
        if ($image === null) {
            $logo = "https://cuernavaca.visorurbano.com/assets/images/logo_cuernavaca.svg";
            return $logo;
        } else {
            $logo = $image;
            return $logo;
        }
    }

    public function getImage($folio)
    {
        $folio = base64_decode($folio);
        $data = DB::table('municipios')
            ->join('consulta_requisitos', 'consulta_requisitos.id_municipio', 'municipios.id')
            ->select('image')
            ->where('consulta_requisitos.folio', $folio)
            ->get();

        $image = $data[0]->image;

        if ($image === null) {
            $logo = "https://cuernavaca.visorurbano.com/assets/images/logo_cuernavaca.svg";
            return $logo;
        } else {
            $logo = $image;
            return $logo;
        }
    }

    public function getImageByID($id)
    {

        $data = DB::table('municipios')
               ->select('image')
            ->where('id', $id)
            ->get();

        $image = $data[0]->image;

        if ($image === null) {
            $logo = "https://cuernavaca.visorurbano.com/assets/images/logo_cuernavaca.svg";
            return $logo;
        } else {
            $logo = $image;
            return $logo;
        }
    }
    public function getImageHistorico($folio)
    {
        $folio = base64_decode($folio);

        $data = DB::table('municipios')
            ->join('historico_licencias_giro', 'historico_licencias_giro.id_municipio', 'municipios.id')
            ->select('image')
            ->where('historico_licencias_giro.id', $folio)
            ->get();

        $image = $data[0]->image;

        if (empty($image)) {
            $logo = "https://cuernavaca.visorurbano.com/assets/images/logo_cuernavaca.svg";
            return $logo;
        } else {
            $logo = $image;
            return $logo;
        }
    }
    public function notifiacionEmailRefrendo($folio)
    {
        $email = $this->tramiteRepository->getNombreSolicitanteRefrendo($folio)['email'];
        try {
            //code...
            $m = Mail::to('sergio@visorurbano.com')->send(new Notificacion($folio));
            Mail::to($email)->send(new Notificacion($folio));
        } catch (\Throwable $th) {
            //return $th;
            //return Mail::failures();
        }
    }

    public function notifiacionEmail($folio)
    {
        $email = $this->tramiteRepository->getNombreSolicitante($folio)['email'];
        try {
            //code...
            $m = Mail::to('sergio@visorurbano.com')->send(new Notificacion($folio));
            Mail::to($email)->send(new Notificacion($folio));
        } catch (\Throwable $th) {
            //return $th;
            //return Mail::failures();
        }
    }
    public function fichaTecnica(Request $request)
    {
        $data = $request->all();
        $direccion = $data['direccion'];
        $metros = $data['metros'];
        $coordenadas = $data['coordenadas'];
        $img = $data['img'];
        //$id_ficha_tecnica_descarga = $data['id_ficha_tecnica_descarga'];
        $municipio_id = $data['municipio_id'];

        $uuid = (string) Str::uuid();
        $ficha = new FichaTecnica();
        $ficha->uuid = $uuid;
        $ficha->direccion = $direccion;
        $ficha->metros = $metros;
        $ficha->coordenadas = $coordenadas;
        $ficha->img = $img;
        $ficha->municipio_id = $municipio_id;
        //$ficha->id_ficha_tecnica_descarga = $id_ficha_tecnica_descarga;
        $ficha->save();
        if ($ficha) {
            return $this->successResponse(array('uuid' => $uuid));
        }
        // return $uuid;

    }

    public function consultaFichaTecnica(Request $request)
    {
        $data = $request->all();
        $ciudad = $data['ciudad'];
        $correo = isset($data['correo']) ? $data['correo'] : "";
        $nombre = isset($data['nombre']) ? $data['nombre'] : "";
        $edad = $data['edad'];
        $sector = $data['sector'];
        $usos = $data['usos'];
        $ficha = new FichaTecnicaDescarga();
        $ficha->ciudad = $ciudad;
        $ficha->correo = $correo;
        $ficha->edad = $edad;
        $ficha->nombre = $nombre;
        $ficha->sector = $sector;
        $ficha->usos = serialize($usos);
        $ficha->save();
        if ($ficha) {
            return $this->successResponse(array('id' => $ficha->id));
        }
        // return $uuid;

    }

    public function fichaTecnicaGet($uuid)
    {
        $ficha = FichaTecnica::where('uuid', $uuid)->first();
        $urlLic = env('APP_URL');
        $coordenadas = $ficha->coordenadas;
        $direccion = $ficha->direccion;
        $metros = $ficha->metros;
        $img = $ficha->img;
        $metros = json_decode($metros);
        $qrCode = new QrCode();
        // var_dump($qrCode);
        // die();
        $qrCode->setText($urlLic . "ficha_tecnica/$uuid");
        // $qrCode->setlogoPath("https://visorurbano.guadalajara.gob.mx/assets/img/logo.png");
        // $qrCode->setSize(500);
        // $qrCode->setLogoSize(500);
        // $qrCode->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0));
        // $qrCode->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0));
        // $qrCode->setLabel('My label');
        // $qrCode->setLabelFontSize(16);
        $qrCode = $qrCode->writeString();
        // var_dump($qrCode);
        // die();
        $qrCode = base64_encode($qrCode);
        $c = json_decode(base64_decode($coordenadas));
        $text_coords = "";
        for ($i = 0; $i < count($c); $i++) {
            $text_coords .= $c[$i][0] . " " . $c[$i][1] . ($i + 1 == count($c) ? "" : ",");
        }
        $coords = "POLYGON (($text_coords))";
        $url = env('GEOSERVER_APP') . "ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:planparcialdesarrollourbano32613&outputFormat=application/json&cql_filter=INTERSECTS(geom, " . $coords . ")";
        $response = $this->curlUrl($url);
        $data = json_decode($response, true);
        $error = false;
        $messageError = '';
        if (isset($data['numberReturned'])) {
            if ($data['numberReturned'] > 0) {
                $features = $data['features'];
                $i = 0;
                $tt = 0;
                foreach ($features as $value) {
                    $clave = $value['properties']['clave_de_clasificacion_de_zona_secundaria'];
                    if ($features[0]['properties']['municipio_id'] == 98 || $features[0]['properties']['municipio_id'] == 39) {
                        $clave = $value['properties']['clave_de_zonificacion'];
                    }
                    $exp = explode(',', $clave);
                    foreach ($exp as $key) {
                        $url2 = env("DJANGO_REST") . "norma-control-edificacion/?municipio={$value['properties']['municipio_id']}&clave={$key}&distrito={$value['properties']['distrito']}";
                        $d = $this->curlUrl($url2);
                        foreach (json_decode($d) as $key2) {
                        }
                        if (count(array($d)) > 0) {

                            if ($features[0]['properties']['municipio_id'] == 39) {
                                if ($value['properties']['sub_distrito'] == $features[$i]['properties']['sub_distrito']) {
                                    $features[$i] = $features[$tt];
                                    $a = 0;
                                    $b = $d;
                                    $array2[1] = null;
                                    foreach (json_decode($d) as $key2) {
                                        $array = (array) $key2;
                                        if ($array['sub_distrito'] == $value['properties']['sub_distrito']) {
                                            $array2[0] = $key2;
                                        }
                                        $a++;
                                    }

                                    $features[$i]['properties']['normas_control_edificacion'] = $array2;
                                    $features[$i]['properties']['clave_act'] = $key;
                                    $i++;
                                }
                            } else {
                                $features[$i] = $features[$tt];
                                $features[$i]['properties']['normas_control_edificacion'] = json_decode($d);
                                $features[$i]['properties']['clave_act'] = $key;
                                $i++;
                            }
                        }
                    }
                    $tt++;
                }

                // return ($features);
                // die();

                $municipio = Municipio::find($features[0]['properties']['municipio_id']);
                $logo = "https://cuernavaca.visorurbano.com/assets/images/logo_cuernavaca.svg";
                if (count($features) == 0) {
                    $error = true;
                    $messageError = 'Count';
                }
            } else {
                $error = true;
                $messageError = 'asd';
            }
        } else {
            $error = true;
            $messageError = 'asd';
        }

        if ($error) {
            $pdf = PDF::loadView('ficha404', ['qr' => $qrCode, 'img' => $img, 'data' => $data, 'metros' => $metros, 'direccion' => $direccion])
                ->setWarnings(false);
            $pdf->setPaper('letter', 'landscape');
            $pdf->setOptions(
                [
                    'isPhpEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                ]
            );
            return $pdf->stream("Ficha_tecnica_" . Carbon::now()->hour . " " . Carbon::now()->second . ".pdf");
        } else {
            $logo = "https://cuernavaca.visorurbano.com/assets/images/logo_cuernavaca.svg";
            $pdf = PDF::loadView('fichaTecnica', ['logo' => $logo, 'qr' => $qrCode, 'img' => $img, 'municipio' => $municipio, 'features' => $features, 'metros' => $metros, 'direccion' => $direccion])
                ->setWarnings(false);
            $pdf->setPaper('letter', 'landscape');
            $pdf->setOptions(
                [
                    'isPhpEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                ]
            );
            return $pdf->stream("Ficha_tecnica_" . Carbon::now()->hour . " " . Carbon::now()->second . ".pdf");
        }
    }

    public function curlUrl($url)
    {

        $urlDecoded = str_replace(' ', '%20', $url);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlDecoded,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function storeFichaSemadet($clave)
    {
        $result = DB::table('poet_tablacentral')
            ->where('clave_1', $clave)
            ->select('*')
            ->first();

          $bbox_result = DB::select(
              DB::raw("SELECT ST_Extent(geom) AS bbox FROM poet_ugas WHERE clave = :clave"),
              array('clave' => $clave)
          );


                    // Extraer el bbox del resultado
            $bbox_string = $bbox_result[0]->bbox;
            $bbox_values = [];
            preg_match('/BOX\((.*?)\)/', $bbox_string, $bbox_values);

            // Ajustar la URL con el bbox obtenido
            $bbox = str_replace(" ", ",", $bbox_values[1]);
            $urlMapa = "https://api.visorurbano.cuernavaca.gob.mx/geoserver/VUJ/wms?service=WMS&version=1.1.0&request=GetMap&layers=VUJ%3Apoet_ugas,VUJ%3Apoet_politicas&bbox=$bbox&width=768&height=523&srs=EPSG%3A4326&styles=&format=image%2Fpng";





        // Si no se encuentra la clave, retornar un error o mensaje
        if (!$result) {
            return response()->json(['message' => 'Clave not found.'], 404);
        }

        // Dividir los valores de id_criterios y id_estrategia en arrays
        $idsCriterios = explode(',', $result->id_criterios);
        $idsEstrategia = explode(',', $result->id_estrategia);

        // Consulta a poet_tablacriterios y poet_tablaestategias
        $criterios = DB::table('poet_tablacriterios')->whereIn('id_criterio', $idsCriterios)->get();
        $estrategias = DB::table('poet_tablaestrategias')->whereIn('id_estrategia', $idsEstrategia)->get();

        if (!$result) {
            $qrCode = ''; // Genera o asigna tu QR Code aquí
            $img = ''; // Asigna la imagen que desees aquí
            $metros = ''; // Asigna la información de metros aquí
            $direccion = ''; // Asigna la dirección aquí

            $pdf = PDF::loadView('ficha404', ['qr' => $qrCode, 'img' => $img, 'data' => $data, 'metros' => $metros, 'direccion' => $direccion])
                ->setWarnings(false)
                ->setPaper('letter', 'landscape')
                ->setOptions([
                    'isPhpEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                ]);
            return $pdf->stream("Ficha_tecnica_" . Carbon::now()->hour . " " . Carbon::now()->second . ".pdf");
        }

        /* Devolver todos los datos
        return response()->json([
        'criterios' => $criterios,
        'estrategias' => $estrategias,
        'tabla_central' => $result
        ]);*/
        $logo = '';
        $qrCode = new QrCode("https://api.visorurbano.cuernavaca.gob.mx/ficha_semadet/$clave");
        $urlLic = env('APP_URL');
        $qrCode->setText($urlLic . "ficha_semadet/$clave");
        $qrCode = $qrCode->writeString();
        $qrCode = base64_encode($qrCode);

        $pdf = PDF::loadView('fichaTecnicaSemadet', [
            'logo' => $logo,
            'qr' => $qrCode,
            'criterios' => $criterios,
            'data' => $result,
            'estrategias' => $estrategias,
            'urlMapa' => $urlMapa
        ])
            ->setWarnings(false)
            ->setPaper('letter', 'landscape')
            ->setOptions([
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);
        return $pdf->stream("Ficha_semadet_" . Carbon::now()->hour . " " . Carbon::now()->second . ".pdf");

    }

    public function fichaTecnicaSemadetGet($uuid)
    {
        // $ficha = FichaSemadet::where('uuid', $uuid)->first();
        $urlLic = env('APP_URL');

        $qrCode = new QrCode();
        $qrCode->setText($urlLic . "fichaSemdet/$uuid");
        $qrCode = $qrCode->writeString();
        $qrCode = base64_encode($qrCode);
        $c = $coordenadas;
        $parts = explode(',', trim($c, '[]'));
        $longitude = floatval(trim($parts[0]));
        $latitude = floatval(trim($parts[1]));
        $c = [[$longitude, $latitude]];
        $text_coords = "";

        $error = false;
        $messageError = '';

        if ($error) {
            $pdf = PDF::loadView('ficha404', ['qr' => $qrCode, 'img' => $img, 'data' => $data, 'metros' => $metros, 'direccion' => $direccion])
                ->setWarnings(false);
            $pdf->setPaper('letter', 'landscape');
            $pdf->setOptions(
                [
                    'isPhpEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                ]
            );
            return $pdf->stream("Ficha_tecnica_" . Carbon::now()->hour . " " . Carbon::now()->second . ".pdf");
        } else {
            $logo = "https://cuernavaca.visorurbano.com/assets/images/logo_cuernavaca.svg";
            $pdf = PDF::loadView('fichaTecnicaSemadet', ['logo' => $logo, 'qr' => $qrCode, 'img' => $img])->setWarnings(false);
            $pdf->setPaper('letter', 'landscape');
            $pdf->setOptions(
                [
                    'isPhpEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                ]
            );
            return $pdf->stream("Ficha_semadet_" . Carbon::now()->hour . " " . Carbon::now()->second . ".pdf");
        }
    }
}
