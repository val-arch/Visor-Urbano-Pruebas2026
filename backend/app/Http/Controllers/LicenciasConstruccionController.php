<?php

namespace App\Http\Controllers;

use App\DTOs\Licencia\LicenciaUpdateDto;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\ApiResponser;
use App\Traits\LogHistoricoConstruccion;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\ITramiteConstruccionRepository;
use App\Repositories\Interfaces\ILicenciaRepository;
use App\Models\LicenciaConstruccionVisor;
use App\Models\ConsultaRequisitoConstruccion;
use App\Models\TipoTramiteConstruccion;
use App\Models\RespuestaConstruccion;
use Illuminate\Support\Facades\Auth;
use App\User;

use App\Mail\Notificacion;
use App\Mail\Notificaciones;
use App\Models\HistoricoLicencia;
use App\Models\NotificacionUser;

use Illuminate\Support\Facades\Mail;
use App\Mail\VentanillaNotificacion;

class LicenciasConstruccionController extends Controller
{
    use ApiResponser;
    use LogHistoricoConstruccion;
    private ITramiteConstruccionRepository $tramiteRepository;
    private ILicenciaRepository $licenciaRepository;

    public function __construct(ITramiteConstruccionRepository $tramiteRepository)
    {
        $this->tramiteRepository = $tramiteRepository;
    }
    public function getLicencias()
    {
        $result =  DB::table('tramite')
            ->join('consulta_requisitos', 'consulta_requisitos.folio', 'tramite.folio')
            ->select('tramite.folio as folio', 'tramite.status as status', 'consulta_requisitos.codigo_scian as codigo', 'consulta_requisitos.nombre_scian as descripcion', 'consulta_requisitos.colonia as colonia', 'consulta_requisitos.municipio as municipio', 'tramite.fecha_inicio_tramite')
            ->get();
        if ($result == false) {
            return $this->errorResponse('No tienes permiso', 403);
        } else {
            return $this->successResponse($result);
        }
    }
    public function updateLicenciaStatus($id, Request $request)
    {
        $currentUser = Auth::user();

        if (($request->file == "null")) {

            $ruta = '';
        } else {
            $filename = $request->file->getClientOriginalName();
            $filename2 = explode('.', $filename);
            $fileNew =  $filename2[0] . '_visor';
            $destinationPath = 'public/licencias_giro_file/' . $currentUser->id_municipio . '/licencias_historico_motivo/';
            $final = $request->file->move(base_path("$destinationPath"), $filename);
            $ruta = 'licencias_giro_file/' . $currentUser->id_municipio . '/licencias_historico_motivo/' . $filename;
        }


        $data = $request->all();
        $data['id'] = $id;
        $entry                   = LicenciaConstruccionVisor::find($data['id']);
        $entry->status_licencia  = $data['status_licencia'];
        $entry->motivo           = $data['motivo'];
        $entry->archivo_motivo   = $ruta;
        $entry->fecha_cambio_status =  Carbon::now();
        $entry->save();

        return $this->successResponse($id, 202);
    }

     public function updateLicenciaFilesProrroga($folio, Request $request)
    {   

        $data = $request->all()['formData'];
        $data2 = $request->all()['formData2'];


        

        $entry = LicenciaConstruccionVisor::where('folio', base64_decode($folio))->first();

        if($entry){

            $entry->nombres_archivos_prorroga = $data;
            $entry->rutas_archivos_prorroga = $data2;
            $entry->save();
            return $this->successResponse(202);
        }
        return $this->successResponse(500);
        
    }

    public function uploadFileProrroga($folio, $inputName,Request $request){

        $this->validate($request,
         [
            'file' => 'mimes:jpeg,png,bmp,tiff,pdf,doc,docx,xls',
            'file' => 'required'
        ]);
        $name = $this->tramiteRepository->file($folio, $inputName ,$request->file('file'));

        return $this->successResponse($name, 202);
    }
    public function updateLicenciaStatusHis($id, Request $request)
    {

        $data = $request->all();
        $str = str_replace(array('\'', '"'), '', $id);
        if (($request->file == "null")) {

            $ruta = '';
        } else {
            $filename = $request->file->getClientOriginalName();
            $filename2 = explode('.', $filename);
            $fileNew =  $filename2[0] . '_visor';
            $destinationPath = 'public/licencias_historico_motivo/' . $data['id_municipio'] . '/';
            $final = $request->file->move(base_path("$destinationPath"), $filename);
            $ruta  = 'licencias_historico_motivo/' . $data['id_municipio'] . '/' . $filename;
        }

        $entry                      = HistoricoLicencia::find($data['id']);
        $entry->status_licencia     = $data['status_licencia'];
        $entry->motivo              = $data['motivo'];
        $entry->archivo_motivo      =   $ruta;
        $entry->fecha_cambio_status = Carbon::now();
        $entry->save();

        return $this->successResponse($id, 202);
    }
    public function getLicencia($folio)
    {
        $folio  = base64_decode($folio);
        $data =  DB::table('tramite')
            ->join('consulta_requisitos', 'consulta_requisitos.folio', 'tramite.folio')
            ->select('tramite.folio as folio', 'tramite.status as status', 'tramite.fecha_inicio_tramite as fecha_inicio', 'consulta_requisitos.codigo_scian as codigo', 'consulta_requisitos.nombre_scian as descripcion')
            ->where('tramite.folio', '=', $folio)
            ->get();

        $data3 = DB::table('consulta_requisitos')
            ->join('tramite', 'consulta_requisitos.folio', '=', 'tramite.folio')
            ->select('consulta_requisitos.*', 'step_actual', 'firma_usuario as f_user')
            ->where('consulta_requisitos.folio', $folio)
            ->get();


        $data2 =  DB::table('notificaciones')
            ->select('comentario', 'fecha_creacion')
            ->where('folio', $folio)
            ->get();


        $nombreDueno = $this->tramiteRepository->getDueno($folio);

        $result = array('data' => $data, 'nombreDueno' => $nombreDueno, 'comentarios' => $data2, 'info' => $data3);


        if ($data == false) {
            return $this->errorResponse('No tienes permiso', 403);
        } else {
            return $this->successResponse($result);
        }
    }
    public function licenciaPagada($id, Request $request)
    {
        $lic = LicenciaConstruccionVisor::find($id);
        $user = Auth::user();
        $id_user = $user->id;
        $userRole = $user->roles_construccion[0]->id;
        if ($lic) {
            $lic->status_pago = 1;
            $lic->fecha_pago = Carbon::now();
            $lic->id_usuario_pago = $id_user;
            $lic->save();
            preg_match("/id=(\w*)/", $lic->url_minimapa, $id_ads);
            $curl = curl_init();
            $curl2 = curl_init();
            $ex = explode('-', $lic->folio);
            curl_setopt_array($curl, array(
                CURLOPT_URL => env('DJANGO_REST') . 'registros-tramite/?folio=' . $lic->folio . '',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                //   CURLOPT_POSTFIELDS =>'{"properties":{"giro":"'.$lic->actividad_comercial.'","municipio":'.$ex[0].'}}',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Token ' . env('TOKEN_DJANGO'),
                    'Content-Type: application/json'
                ),
            ));
            $response = curl_exec($curl);
            if ($response) {
                $response = json_decode($response);
                curl_setopt_array($curl2, array(
                    CURLOPT_URL => env('DJANGO_REST') . 'registros-tramite-geom/' . $response[0]->id . '/',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'PATCH',
                    CURLOPT_POSTFIELDS => '{"properties":{"giro":"' . $lic->actividad_comercial . '","municipio":' . $ex[0] . '}}',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Token ' . env('TOKEN_DJANGO'),
                        'Content-Type: application/json'
                    ),
                ));

                $response2 = curl_exec($curl2);
            }


            $data2          = json_encode($request->except(['token']));
            $valor_anterior = $lic->folio;
            $this->historialConstruccion($accion = 'Se cambio el estatus de una licencia a pagada ', $valor_anterior, $data2, $tipo = 3, 0);


            curl_close($curl);
            return $this->successResponse($lic);
        } else {
            return $this->errorResponse('No existe la licencia', 400);
        }
    }
    public function licenciaPagadaHis($id, Request $request)
    {
        $lic = HistoricoLicencia::find($id);
        $user = Auth::user();

        $id_user = $user->id;
        $userRole = $user->roles_construccion[0]->id;
        if ($lic) {
            $lic->status_pago = 1;
            $lic->fecha_pago = Carbon::now();
            $lic->id_usuario_pago = $id_user;
            $lic->save();
            $this->notifiacionEmailRefrendo($lic->folio_licencia, $lic->email);
            $data2          = json_encode($request->except(['token']));
            $valor_anterior = $lic->folio;
            $this->historialConstruccion($accion = 'Se cambio el estatus de una licencia a pagada ', $valor_anterior, $data2, $tipo = 3, 0);
            //  curl_close($curl);
            return $this->successResponse($lic);
        } else {
            return $this->errorResponse('No existe la licencia', 400);
        }
    }
    public function getInfoLicencia($id)
    {

        $data3 = DB::table('licencias_giro_visor')
            ->select('consulta_requisitos.*', 'step_actual', 'firma_usuario as f_user')
            ->where('consulta_requisitos.folio', $id)
            ->get();
        return $this->successResponse($data3);
    }


    public function getFilesProrroga($folio)
    {
        $data3 = DB::table('licencias_construccion_visor')
            ->select('nombres_archivos_prorroga', 'rutas_archivos_prorroga')
            ->where('folio', base64_decode($folio))->where('tipo_licencia', 'Nueva')
            ->get();
        return $this->successResponse($data3);
    }

    
    public function licenciaBaja($id, Request $request)
    {
        $data = $request->all();
        $lic = LicenciaConstruccionVisor::find($id);
        $user = Auth::user();
        $id_user = $user->id;
        $userRole = $user->roles_construccion[0]->id;
        if ($lic) {
            $lic->status_baja = 1;
            $lic->fecha_baja = Carbon::now();
            $lic->motivo_baja = $data['motivo'];
            $lic->id_usuario_baja = $id_user;
            $lic->save();
            $email = $this->tramiteRepository->getNombreSolicitante($lic->folio)['email'];
            $this->notifiacionEmail($lic->folio, $email);
            $this->bajaNotificacionUser($lic->folio, $email, $data['motivo'], $userRole);
            $data2          = json_encode($request->except(['token']));
            $valor_anterior = '';
            $this->historialConstruccion($accion = 'Se dio de baja una licencia', $valor_anterior, $data2, $tipo = 3, 0);
            return $this->successResponse($lic);
        } else {
            return $this->errorResponse('No existe la licencia', 400);
        }
    }
    public function notifiacionEmail($folio, $email)
    {
        try {
            //code...
            $m = Mail::to('sergio@visorurbano.com')->send(new Notificacion($folio));
            Mail::to($email)->send(new Notificacion($folio));
        } catch (\Throwable $th) {
            //return $th;
            //return Mail::failures();
        }
    }
    public function notifiacionEmailRefrendo($folio, $email)
    {
        try {
            //code...
            $m = Mail::to('sergio@visorurbano.com')->send(new Notificacion($folio));
            Mail::to($email)->send(new Notificaciones($folio));
        } catch (\Throwable $th) {
            //return $th;
            //return Mail::failures();
        }
    }
    public function bajaNotificacionUser($folio, $email, $motivo, $role)
    {
        $userE = User::where(['email' => $email])->first();
        $userNotificacion =  $userE->id ?? 0;
        $noti = new NotificacionUser;
        $noti->id_usuario = $userNotificacion;
        $noti->folio = $folio;
        // $noti->id_tramite = 0;
        $noti->email_solicitante = $email;
        $noti->comentario = $motivo;
        $noti->fecha_creacion = Carbon::now();
        //$noti->archivo_dependencia = env('APP_URL').$fileInsert;
        $noti->notificado = 0;
        $noti->type = 1;
        $noti->dependencia_notifica = $role;
        $noti->save();
    }

    public function subirPdfFirmado($id, Request $request)
    {
        $data = $request->all();
        $lic = LicenciaConstruccionVisor::find($id);
        $user = Auth::user();
        $id_user = $user->id;
        $userRole = $user->roles_construccion[0]->id;
        $query2 =  DB::table('consulta_requisitos_construccion')
            ->select('*')
            ->where('folio', $lic->folio)
            ->first();
        $idConsulta = $query2->id;
        if ($request->file('licencia')) {
            $fecha1 = Carbon::now()->format('Y-m-d-H-i-s');
            $filename = $request->licencia->getClientOriginalName();
            $filename2 = explode('.', $filename);
            //crear una carpeta por cada tramite
            $fileNew =  'licencia-' . str_replace('/', '-', $lic->folio) . '-' . $fecha1 . '.' . $filename2[1];
            $destinationPath = 'public/licencias_giro_file/' . $idConsulta . '/lic';
            $final = $request->licencia->move(base_path("$destinationPath"), $fileNew);
            $fileInsert = str_replace('public/', '', $destinationPath) . '/' . $fileNew;
            // return $final;
            $lic->pdf_escaneado = env('APP_URL') . $fileInsert;
            $lic->save();
            return $this->successResponse(
                env('APP_URL') . $fileInsert,
                202
            );
        }
    }

    public function subirPdfFirmadoHis($id, Request $request)
    {
        $data = $request->all();
        $lic = HistoricoLicencia::find($id);

        if ($request->file('licencia')) {
            $fecha1 = Carbon::now()->format('Y-m-d-H-i-s');
            $filename = $request->licencia->getClientOriginalName();
            $filename2 = explode('.', $filename);
            //crear una carpeta por cada tramite
            $fileNew =  'licencia-' . str_replace('/', '-', $lic->folio_licencia) . '-' . $fecha1 . '.' . $filename2[1];
            $destinationPath = 'public/licencias_giro_file/historico/' . $lic->id . '/lic';
            $final = $request->licencia->move(base_path("$destinationPath"), $fileNew);
            $fileInsert = str_replace('public/', '', $destinationPath) . '/' . $fileNew;
            // return $final;
            $lic->pdf_escaneado = env('APP_URL') . $fileInsert;
            $lic->save();
            return $this->successResponse(
                env('APP_URL') . $fileInsert,
                202
            );
        }
    }

    public function getBoletin()
    {

        $result2 =  DB::table('historico_licencias_giro')
        ->selectRaw(' folio_licencia folio ,
        descripcion_detallada actividad_comercial,
        codigo_giro codigo_scian,
        (select nombre from municipios where id =id_municipio ) 
        municipio,colonia_titular colonia')
        ->where('tipo_licencia', 'Refrendo');
        $result =  DB::table('licencias_giro_visor')
            ->join('consulta_requisitos', 'licencias_giro_visor.folio', 'consulta_requisitos.folio')
            ->select('licencias_giro_visor.folio', 'licencias_giro_visor.actividad_comercial', 'licencias_giro_visor.codigo_scian', 'consulta_requisitos.municipio', 'consulta_requisitos.colonia')
            ->union($result2)
            ->paginate(20);
        return $this->successResponse($result);
    }

    public function enviarLicenciaInteresados($folio){
        $licencia = LicenciaConstruccionVisor::where('folio', base64_decode($folio))->first();

        $consulta = ConsultaRequisitoConstruccion::where('folio', base64_decode($folio))->first();

        $correo_int = RespuestaConstruccion::where('id_tramite', $consulta->id)->where('name', 'int_correo')->first();
        $correo_int = $correo_int->value;

        $correo_prop = RespuestaConstruccion::where('id_tramite', $consulta->id)->where('name', 'prop_correo')->first();
        $correo_prop = $correo_prop->value;

        $nombre_tramite = TipoTramiteConstruccion::where('id', $consulta->tramite_relacionado)->first();
        $nombre_tramite = $nombre_tramite->tramite;

        $link = env('APP_URL')."licenciaConstruccionById/".base64_encode($licencia->id);

        if($correo_int == $correo_prop){
            Mail::to($correo_int)->send(new VentanillaNotificacion($nombre_tramite, $folio, $link));
            return $this->successResponse(200);
        }else{
            Mail::to($correo_int)->send(new VentanillaNotificacion($nombre_tramite, $folio, $link));
            Mail::to($correo_prop)->send(new VentanillaNotificacion($nombre_tramite, $folio, $link));
            return $this->successResponse(200);
        }
    }
}
