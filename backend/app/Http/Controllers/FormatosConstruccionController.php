<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\Notificacion;
use App\Models\AperturaProvisional;
use App\Models\FichaTecnica;
use App\Models\FirmaMunicipioConstruccion;
use App\Models\LicenciaConstruccionVisor;
use App\Models\MunicipioConstruccion;
use App\Models\NotificacionUser;
use App\Models\ResolucionConstruccionDependencias;
use App\Models\RevisionesConstruccion;
use App\Models\TramiteConstruccion;
use App\Repositories\Interfaces\ITramiteConstruccionRepository;
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
use App\Models\RespuestasTemplatesTramiteConstruccion;

class FormatosConstruccionController extends Controller
{

    private ITramiteConstruccionRepository $tramiteRepository;
    use LogoMunicipio;
    use ApiResponser;
    use LogHistorico;

    public function __construct(ITramiteConstruccionRepository $tramiteRepository)
    {
        $this->tramiteRepository = $tramiteRepository;
    }

    public function generarLicencia($folio, Request $request)
    {
        $data = $request->all();
        $tipo_licencia = "Nueva";
        $status_licencia = "Vigente";
        $logo = $this->getImage($folio);
        $folio = base64_decode($folio);

        $duenoData = $this->tramiteRepository->getDataDueno2($folio);
        $user = Auth::user();
        $role = $user->roles_construccion[0]->id;
        $result = DB::table('consulta_requisitos_construccion')
            ->select('*')
            ->where('folio', $folio)
            ->get();
        $muni = DB::table('municipios_construccion')
            ->where('id', $result[0]->id_municipio)
            ->first();

        $id_municipio = $result[0]->id_municipio;
        $firma = $muni->firma_director == null ? '' : $muni->firma_director;
        $lic = LicenciaConstruccionVisor::where(['folio' => $folio, 'anio_licencia' => date('Y')])->get();
        $firmas = array();
        $tramite = DB::table('tipo_tramites_construccion')->where('id', $result[0]->tramite_relacionado)->where('id_municipio', $result[0]->id_municipio)->get();

        $tipo_tramite = explode('-', $tramite[0]->folio_interno);

        $latestRecord = LicenciaConstruccionVisor::where('consecutivo', 'LIKE', '%' . $tipo_tramite[0] . '%')
        ->where('municipio_id', $result[0]->id_municipio)
        ->orderBy('id', 'desc')
        ->first();
        $year = Carbon::now()->year;
        if (!is_null($latestRecord)) {
            $folio_internos = explode('-', $latestRecord->consecutivo);
            $folio_internos2 = explode('/', $folio_internos[1]);
            $consecutivo  = $folio_internos[0] . "-" . ($folio_internos2[0] + 1) ."/" . $year;
        } else {
            // split folio_interno on dash
            $folio_internos = explode('-', $tramite[0]->folio_interno);
            $folio_internos2 = explode('/', $folio_internos[1]);
            $consecutivo  = $folio_internos[0] . "-" . ($folio_internos2[0] + 1) ."/" . $year;
        }

        if (count($lic) == 0) {
            $firmas_temp = $data['firmas'];
            foreach ($firmas_temp as $value) {
                $firma2 = FirmaMunicipioConstruccion::where('id', $value)->first();
                array_push($firmas, $firma2);
            }
            $currentUser = Auth::user();
            $cout_lics = LicenciaConstruccionVisor::where('municipio_id', $id_municipio)->max('numero_lic');
            if ($muni->folio_init > $cout_lics) {

                $cout_lics = $muni->folio_init;
            } else {
                $cout_lics = $cout_lics + 1;
            }
            $lic = LicenciaConstruccionVisor::create([
                'folio' => $folio,
                'dueno' => $duenoData['nombre'],
                'tipo_construccion' => '',
                'img_logo' => $logo,
                'consecutivo' => $consecutivo,
                'firma' => $firma,
                'url_minimapa' => $result[0]->url_minimapa,
                'anio_licencia' => date('Y'),
                'id_usuario_genero' => $currentUser->id,
                'firmantes' => $firmas,
                'municipio_id' => $id_municipio,
                'numero_lic' => $cout_lics,
                'status_licencia' => $status_licencia,
            ]);
        }
        $lic = LicenciaConstruccionVisor::where(['folio' => $folio])->first();
        if ($lic) {
            // return $final;
            $t = TramiteConstruccion::where('folio', $folio)->update(
                ['pdf_licencia' => "licenciaConstruccionById/" . base64_encode($lic->id)]
            );
            $tramiteSolv = TramiteConstruccion::where('folio', $folio)->first();
            $this->notifiacionEmail($folio);
            $email = $this->tramiteRepository->getNombreSolicitante($folio)['email'];
            $userE = User::where(['email' => $email])->first();
            $userNotificacion = $userE->id ?? 0;
            if ($tramiteSolv->id_usuario != 0 && $tramiteSolv->id_usuario_ventanilla == 0) {
                $userNotificacion = $tramiteSolv->id_usuario;
                $email = User::find($tramiteSolv->id_usuario)->id;
            }

            $revisionesb = RevisionesConstruccion::where(['folio' => $folio, 'rol' => $role])->first();
            $resolucionD = new ResolucionConstruccionDependencias();
            $resolucionD->id_tramite = $revisionesb->id_tramite;
            $resolucionD->rol = $role;
            $resolucionD->id_usuario = $user->id;
            $resolucionD->resolucion_status = 5;
            $resolucionD->resolucion_text = 'Emitio Licencia de Construccion';
            $resolucionD->resolucion_archivo = null;
            $resolucionD->id_licencias_construccion_visor = $lic->id;
            $resolucionD->save();

            $urlLic = env('APP_URL') . "licenciaConstruccionById/" . base64_encode($lic->id);
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
            //$this->historial($accion = 'Se emitio una licencia', $valor_anterior, $data2, $tipo = 5, 0);
            return $this->successResponse($urlLic);
        }
        return $this->errorResponse('No tienes permiso', 403);

    }

    public function generarProrroga($folio, Request $request)
    {

        $data = $request->all();

        $status_licencia = "Vigente";
        $logo = $this->getImage($folio);
        $folio = base64_decode($folio);
        $user = Auth::user();
        $role = $user->roles_construccion[0]->id;

        $duenoData = $this->tramiteRepository->getDataDueno2($folio);

        $result = DB::table('consulta_requisitos_construccion')
            ->select('*')
            ->where('folio', $folio)
            ->get();

        $muni = DB::table('municipios_construccion')
            ->where('id', $result[0]->id_municipio)
            ->first();

        $id_municipio = $result[0]->id_municipio;
        $firma = $muni->firma_director == null ? '' : $muni->firma_director;
        $lic2 = LicenciaConstruccionVisor::where(['folio' => $folio, 'anio_licencia' => date('Y')])->get();
        $firmas = array();
        $firmas_temp = $data['firmas'];
        foreach ($firmas_temp as $value) {
            $firma2 = FirmaMunicipioConstruccion::where('id', $value)->first();
            array_push($firmas, $firma2);
        }

        $currentUser = Auth::user();
        $cout_lics = LicenciaConstruccionVisor::where('municipio_id', $id_municipio)->max('id');
        $folioTmp = str_replace("/", "-", $folio);
        $lic = LicenciaConstruccionVisor::create([
            'folio' => $folio,
            'tipo_construccion' => '',
            'dueno' => $duenoData['nombre'],
            'apellido_p' => $duenoData['apellido_p'],
            'apellido_m' => $duenoData['apellido_m'],
            'curp' => $duenoData['curp'],
            'observaciones' => $data['observaciones'],
            'img_logo' => $logo,
            'consecutivo' => ($cout_lics + 1) . '/' . date('Y'),
            'firma' => $firma,
            'url_minimapa' => $result[0]->url_minimapa,
            'anio_licencia' => date('Y'),
            'id_usuario_genero' => $currentUser->id,
            'firmantes' => $firmas,
            'municipio_id' => $id_municipio,
            'numero_lic' => $lic2[0]->numero_lic,
            'status_licencia' => $status_licencia,
            'tipo_licencia' => 'Prórroga',
            'urlLicenciaPdf' => 'public/formatosvisor/' . $user->id_municipio . '/LicenciaConstruccion ' . $folioTmp . '.pdf',
        ]);

        if ($lic) {
            // return $final;
            $t = TramiteConstruccion::where('folio', $folio)->update(
                ['pdf_licencia' => "licenciaConstruccionById/" . base64_encode($lic->id)]
            );
            $tramiteSolv = TramiteConstruccion::where('folio', $folio)->first();
            $this->notifiacionEmail($folio);
            $email = $this->tramiteRepository->getNombreSolicitante($folio)['email'];
            $userE = User::where(['email' => $email])->first();
            $userNotificacion = $userE->id ?? 0;
            if ($tramiteSolv->id_usuario != 0 && $tramiteSolv->id_usuario_ventanilla == 0) {
                $userNotificacion = $tramiteSolv->id_usuario;
                $email = User::find($tramiteSolv->id_usuario)->id;
            }
            $urlLic = env('APP_URL') . "licenciaConstruccionById/" . base64_encode($lic->id);
            $noti = new NotificacionUser;
            $noti->id_usuario = $userNotificacion;
            $noti->folio = $folio;

            $revisionesb = RevisionesConstruccion::where(['folio' => $folio, 'rol' => $role])->first();
            $resolucionD = new ResolucionConstruccionDependencias();
            $resolucionD->id_tramite = $revisionesb->id_tramite;
            $resolucionD->rol = $role;
            $resolucionD->id_usuario = $user->id;
            $resolucionD->resolucion_status = 6;
            $resolucionD->resolucion_text = 'Emitio Prorroga';
            $resolucionD->resolucion_archivo = null;
            $resolucionD->id_licencias_construccion_visor = $lic->id;
            $resolucionD->save();

            // $noti->id_tramite = 0;
            $noti->email_solicitante = $email;
            $noti->comentario = 'Prorroga emitida!!';
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

    public function licenciaGiroRef($folio, $anio, $numero, $id)
    {

        $logo = $this->getImage($folio);
        $urlLic = env('APP_URL');
        $qrCode = new QrCode($urlLic . "licenciaConstruccion/$folio/$anio/$numero/$id");
        $qrCode = $qrCode->writeString();
        $qrCode = base64_encode($qrCode);
        $respuesta = self::getPdfValues($folio);
        $ubicacion = $respuesta['domicilio']['calle'] . ' número ' . $respuesta['domicilio']['exterior'] .
            (isset($respuesta['domicilio']['interior']) ? $respuesta['domicilio']['interior'] : '') . ' colonia ' . $respuesta['domicilio']['colonia'];
        $folio = base64_decode($folio);
        $anio = base64_decode($anio);
        $tip = base64_decode($numero);
        $id = base64_decode($id);

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
        $firmas = FirmaMunicipioConstruccion::where(['id_municipio' => $result->id_municipio])->orderBy('orden', 'asc')->get();

        foreach ($firmas as $value) {

            $firmasF[$value->orden] = $value;
        }

        $muniData = MunicipioConstruccion::find($result->id_municipio);
        $descripcion = DB::table('respuestas')->where(['id_tramite' => $result->id, 'name' => 'descripcion_actividad'])->first();

        $mes = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $lic = LicenciaConstruccionVisor::where(['folio' => $folio, 'anio_licencia' => $anio, 'tipo_licencia' => $tipo])->first();
        if ($lic->pdf_escaneado != '') {
            return redirect($lic->pdf_escaneado);
        }

        $pdf = PDF::loadView('licenciaNegocio', [
            "data" => $lic, "firmas" => $firmasF, "mes" => $mes, "dueno" => $dueno, "result" => $result,
            'ubicacion' => $ubicacion, "data_muni" => $muniData, "curp" => $curp_proprietario, "qrCode" => $qrCode, "logo" => $logo, "desc" => $descripcion,
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
        return $pdf->stream("Licencia de construccion.pdf");
    }
    public function licenciaConstruccion($id, $anio)
    {
        $folio = $id;

        $logo = $this->getImage($folio);


        $urlLic = env('APP_URL');
        $qrCode = new QrCode($urlLic . "licenciaConstruccion/$folio/$anio");
        $qrCode = $qrCode->writeString();
        $qrCode = base64_encode($qrCode);
        $respuesta = self::getPdfValues($folio);

        $ubicacion = $respuesta['domicilio']['calle'] . ' número ' . $respuesta['domicilio']['exterior'] .
            (isset($respuesta['domicilio']['interior']) ? $respuesta['domicilio']['interior'] : '') . ' colonia ' . $respuesta['domicilio']['colonia'];
        $folio = base64_decode($folio);
        $anio = base64_decode($anio);

        $result = DB::table('consulta_requisitos_construccion')
            ->select('*')
            ->where('folio', $folio)
            ->first();
        $tipo = 'Nueva';

        $firma = '';
        $dueno = $this->tramiteRepository->getDataDueno($folio);
        $firmasF = [];
        $duenoF = [];
        $firmas = FirmaMunicipioConstruccion::where(['id_municipio' => $result->id_municipio])->get();
        foreach ($firmas as $value) {
            $firmasF[$value->orden] = $value;
        }
        $muniData = MunicipioConstruccion::find($result->id_municipio);
        $descripcion = DB::table('respuestas_construccion')->where(['id_tramite' => $result->id, 'name' => 'descripcion_actividad'])->first();

        $mes = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $lic = LicenciaConstruccionVisor::where(['folio' => $folio, 'anio_licencia' => $anio])->first();
        if ($lic->pdf_escaneado != '') {
            return redirect($lic->pdf_escaneado);
        }
        $dueno = (object) $dueno;
        $pdf = PDF::loadView('licenciaConstruccion', [
            "data" => $lic, "firmas" => $firmasF, "mes" => $mes, "result" => $result, 'ubicacion' => $ubicacion, "data_muni" => $muniData, "qrCode" => $qrCode, "logo" => $logo, "desc" => $descripcion, "data_dueno" => $dueno,
        ])
            ->setWarnings(false);
        $pdf->setPaper('legal', 'portrait');
        $pdf->setOptions(
            [
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]
        );
        //$pdf->render();
        //print_r($dueno);
        return $pdf->stream("Licencia de construccion.pdf");
    }

    public function licenciaConstruccionById($id)
    {
        $id = base64_decode($id);
        $result = DB::table('licencias_construccion_visor')->where(['id' => $id])->first();

        $folio = base64_encode($result->folio);
        $logo = $this->getImage($folio);

        $anio = base64_encode($result->anio_licencia);
        $tipo = $result->tipo_licencia;
        $urlLic = env('APP_URL');
        $qrCode = new QrCode($urlLic . "licenciaConstruccionById/" . base64_encode($id));
        $qrCode = $qrCode->writeString();
        $qrCode = base64_encode($qrCode);
        $respuesta = self::getPdfValues($folio);

        //campos_contruccion->tramite_relacionado->info_licencia=true show
        $ubicacion = $respuesta['domicilio']['calle'] . ' número ' . $respuesta['domicilio']['exterior'] .
            (isset($respuesta['domicilio']['interior']) ? $respuesta['domicilio']['interior'] : '') . ' colonia ' . $respuesta['domicilio']['colonia'];
        $folio = base64_decode($folio);
        $anio = base64_decode($anio);

        $result = DB::table('consulta_requisitos_construccion')
            ->select('*')
            ->where('folio', $folio)
            ->first();
        $result2 = DB::table('campos_construccion')
        ->whereIn('tramite_relacionado', [$result->tramite_relacionado, 0])
        ->where('active', true)
        ->where('info_licencia', true)->get();
        $result3 = DB::table('tipo_tramites_construccion')->where(['id' => $result->tramite_relacionado])->first();
        $firma = '';
        $dueno = $this->tramiteRepository->getDataDueno($folio);

        $firmasF = [];
        $duenoF = [];
        $firmas = FirmaMunicipioConstruccion::where(['id_municipio' => $result->id_municipio])->orderBy('id', 'asc')->get();
        foreach ($firmas as $value) {
            $firmasF[$value->orden] = $value;
        }

        $muniData = MunicipioConstruccion::find($result->id_municipio);
        $calle_uno = DB::table('respuestas_construccion')->where('id_tramite', $result->id)
        ->where('name', 'entre_calleuno')->first();
        $calle_dos = DB::table('respuestas_construccion')->where('id_tramite', $result->id)
        ->where('name', 'entre_calledos')->first();
        $obra_cp = DB::table('respuestas_construccion')->where('id_tramite', $result->id)
        ->where('name', 'obra_cp')->first();

        $mes = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $lic = LicenciaConstruccionVisor::where(['id' => $id])->first();
        if ($lic->pdf_escaneado != '') {
            return redirect($lic->pdf_escaneado);
        }

        $tramite = DB::table('tramite_construccion')->where('folio', $lic->folio)->first();

        $resolutivo = RespuestasTemplatesTramiteConstruccion::where('id_tramite_construccion', $tramite->id)->orderBy('id_campo', 'asc')->get();

        $dueno = (object) $dueno;

        $pdf = PDF::loadView('licenciaConstruccion', [
            "data" => $lic,
            "firmas" => $firmasF,
            "mes" => $mes,
            "result" => $result,
            'ubicacion' => $ubicacion,
            "data_muni" => $muniData,
            "qrCode" => $qrCode,
            "logo" => $logo,
            "calle_uno" => $calle_uno,
            "calle_dos" => $calle_dos,
            "obra_cp" => $obra_cp,
            "data_campos" => $dueno,
            "result2" => $result2,
            "result3" => $result3,
            "resolutivo"=>$resolutivo,
            "respuesta"=> $respuesta

        ])
            ->setWarnings(false);
        $pdf->setPaper('legal', 'portrait');
        $pdf->setOptions(
            [
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]
        );
        //$pdf->render();
        //print_r($dueno);
        $path = public_path();
        $folioTmp = str_replace("/", "-", $folio);
        $dirname = $path . '/formatosvisor/' . $result->id_municipio . '/' . $id;
        if (!is_dir($dirname)) {
            mkdir($dirname, 0777, true);
        }

        $pdf->save($dirname . '/LicenciaConstruccion ' . $folioTmp . '.pdf');
        DB::table('licencias_construccion_visor')->where('id', $id)->update(['urlLicenciaPdf' => 'public/formatosvisor/' . $result->id_municipio . '/' . $id . '/Licencia ' . $tipo . ' ' . $folioTmp . '.pdf']);
        return $pdf->stream("Licencia de construccion.pdf");
    }

    public function aperturaProvisional($folio)
    {

        $logo = $this->getImage($folio);
        $qrCode = new QrCode("https://api-visorurbano.jalisco.gob.mx/aperturaProvisional/$folio");
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
        $qrCode = new QrCode("https://api-visorurbano.jalisco.gob.mx/acuseNoFirmado/$folio");
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

        $result = DB::table('consulta_requisitos_construccion')
            ->select('*')
            ->where('folio', $folio)
            ->get();
        $result_tipo = DB::table('tipo_tramites_construccion')
            ->select('*')
            ->where('id', $result[0]->tramite_relacionado)
            ->get();

        $idConsulta = $result[0]->id;

        $calle_predio = $this->tramiteRepository->getRespuesta($idConsulta, 'obra_calle');

        $numero_exterior_predio = $this->tramiteRepository->getRespuesta($idConsulta, 'obra_no_ext');

        $numero_interior_predio = $this->tramiteRepository->getRespuesta($idConsulta, 'obra_no_int');
        $numero_interior_predio = $numero_interior_predio != '' ? ' int ' . $numero_interior_predio : '';
        $colonia_predio = $this->tramiteRepository->getRespuesta($idConsulta, 'obra_colonia');
        $cp_predio = $this->tramiteRepository->getRespuesta($idConsulta, 'obra_cp');
        $cp_predio = $cp_predio != '' ? ' con CP ' . $cp_predio : '';

        $domicilio_predio = array(
            "calle" => $calle_predio,
            "exterior" => $numero_exterior_predio,
            "interior" => $numero_interior_predio,
            "colonia" => $colonia_predio,
            "cp" => $cp_predio,
        );

        $nombre = $this->tramiteRepository->getRespuesta($idConsulta, 'int_nombre');
        $apellido_1 = $this->tramiteRepository->getRespuesta($idConsulta, 'int_apellidouno');
        $apellido_2 = $this->tramiteRepository->getRespuesta($idConsulta, 'int_apellidodos');
        $correo = $this->tramiteRepository->getRespuesta($idConsulta, 'int_correo');
        $cel = $this->tramiteRepository->getRespuesta($idConsulta, 'int_cel');

        $data2 = array(
            "nombre_completo" => $nombre . ' ' . $apellido_1 . ' ' . $apellido_2,
            "correo" => $correo,
            "cel" => $cel,
        );

        $data = DB::table('campos_construccion')
            ->select('respuestas_construccion.name', 'respuestas_construccion.value', 'campos_construccion.description')
            ->join('respuestas_construccion', 'campos_construccion.name', '=', 'respuestas_construccion.name')
            ->where('id_tramite', '=', $idConsulta)
            ->get();

        $name = $this->tramiteRepository->getNombreSolicitante($folio)['nombre'];
        $datos = array("result" => $result, "name" => $name, "data" => $data, "data2" => $data2, "domicilio" => $domicilio_predio, 'result_tipo' => $result_tipo);

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


        $pdf = PDF::loadView('cartaResponsivaConstruccion', ["data" => $dataCarta, 'logo' => $logo, "hora_actual" => Carbon::now('GMT-6')->format('H:i')])->setWarnings(false);
        $pdf->setOptions(
            [
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]
        );

        return $pdf->stream('carta Responsiva.pdf');
    }

    public function cartaResponsivaConstruccion($folio)
    {
        $logo = $this->getImage($folio);
        $dataCarta = self::getPdfValues($folio);

        $pdf = PDF::loadView('cartaResponsivaConstruccion', ["data" => $dataCarta, 'logo' => $logo])->setWarnings(false);
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
        $logo = $this->getImage($folio);
        $qrCode = new QrCode("https://api-visorurbano.jalisco.gob.mx/acuseFirmadoConstruccion/$folio");
        $qrCode = $qrCode->writeString();
        $qrCode = base64_encode($qrCode);

        $dataCarta = self::getPdfValues($folio);
        $pdf = PDF::loadView('recepcionEnvio', ["data" => $dataCarta, "qrCode" => $qrCode, 'logo' => $logo])->setWarnings(false);
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
        $qrCode = new QrCode("https://api-visorurbano.jalisco.gob.mx/acuseVentanilla/$folio");
        $qrCode = $qrCode->writeString();
        $qrCode = base64_encode($qrCode);
        $dataCarta = self::getPdfValues($folio);

        $apertura = AperturaProvisional::where(['folio' => $folio, 'contador' => 1])->get();
        //print_r($dataCarta);
        $pdf = PDF::loadView('acuseVentanillaConstruccion', ["data" => $dataCarta, "qrCode" => $qrCode, 'logo' => $logo, 'apertura' => $apertura])->setWarnings(false);
        $pdf->setOptions(
            [
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]
        );

        return $pdf->stream('Acuse de inicio de trámite.pdf');
    }

    public function getImage($folio)
    {
        $folio = base64_decode($folio);
        $data = DB::table('municipios_construccion')
            ->join('consulta_requisitos_construccion', 'consulta_requisitos_construccion.municipio', 'municipios_construccion.nombre')
            ->select('image')
            ->where('consulta_requisitos_construccion.folio', $folio)
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

    public function notifiacionEmail($folio)
    {
        $email = $this->tramiteRepository->getNombreSolicitante($folio)['email'];
        try {
            //code...
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
        $municipio_id = $data['municipio_id'];

        $uuid = (string) Str::uuid();
        $ficha = new FichaTecnica();
        $ficha->uuid = $uuid;
        $ficha->direccion = $direccion;
        $ficha->metros = $metros;
        $ficha->coordenadas = $coordenadas;
        $ficha->img = $img;
        $ficha->municipio_id = $municipio_id;
        $ficha->save();
        if ($ficha) {
            return $this->successResponse(array('uuid' => $uuid));
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
                    if ($features[0]['properties']['municipio_id'] == 98) {
                        $clave = $value['properties']['clave_de_zonificacion'];
                    }

                    $exp = explode(',', $clave);
                    foreach ($exp as $key) {
                        $url2 = env("DJANGO_REST") . "norma-control-edificacion/?municipio={$value['properties']['municipio_id']}&clave={$key}&distrito={$value['properties']['distrito']}";
                        $d = $this->curlUrl($url2);
                        if (count(array($d)) > 0) {
                            $features[$i] = $features[$tt];
                            $features[$i]['properties']['normas_control_edificacion'] = json_decode($d);
                            $features[$i]['properties']['clave_act'] = $key;
                            $i++;
                        }
                    }
                    $tt++;
                }
                // return ($features);
                // die();

                $municipio = MunicipioConstruccion::find($features[0]['properties']['municipio_id']);
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
}
