<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use App\Traits\LogHistoricoConstruccion;
use App\User;
use App\Models\NotificacionUserConstruccion;
use App\Models\FirmaMunicipioConstruccion;
use App\Models\LicenciaConstruccionVisor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Mail\Notificacion;
use App\Models\MunicipioConstruccion;
use App\Models\ResolucionConstruccionDependencias;
use App\Models\RevisionesConstruccion;
use App\Models\SolventacionConstruccion;
use App\Models\TramiteConstruccion;
use App\Models\RoleConstruccion;
use App\Models\ResolucionConstruccion;
use Illuminate\Support\Facades\Mail;
use App\Repositories\Interfaces\ITramiteConstruccionRepository;
use App\Models\Usuario;
use App\Mail\notificacionRolesConstruccion;
use App\Models\TipoTramiteConstruccion;
use App\Models\RespuestaConstruccion;
use App\Mail\notificacionPrevencionConstruccion;



class RevisionConstruccionController extends Controller
{
    use ApiResponser;
    use LogHistoricoConstruccion;
    private ITramiteConstruccionRepository $tramiteRepository;

    public function __construct(ITramiteConstruccionRepository $tramiteRepository)
    {
        $this->tramiteRepository = $tramiteRepository;
    }


    public function insertRevisiones()
    {
    }

    public function getResolucionInfo($folio)
    {
        $folio = base64_decode($folio);
        $user = Auth::user();
        $usuarioActual_id = $user->id;
        $role = $user->roles_construccion[0]->id;
        $consulta =  DB::table('consulta_requisitos_construccion')
            ->select('*')
            ->where('folio', $folio)
            ->first();
        if (!$consulta) {
            return $this->errorResponse('No existe folio', 402);
        }
        /*if (($consulta->id_municipio != $user->id_municipio) || ($role < 2)) {
            return $this->errorResponse('Sin acceso', 403);
        }*/

        $data = [];
     ///   $revisionesb = RevisionesConstruccion::where(['folio' => $folio, 'rol' => 3])->first();
     $revisionesb = RevisionesConstruccion::where(['folio' => $folio])->first();
        $resolucionDependencia = DB::table('resolucion_dependencias_construccion')
            ->join('users', 'users.id', '=', 'resolucion_dependencias_construccion.id_usuario')
            ->join('roles_construccion', 'roles_construccion.id', '=', 'resolucion_dependencias_construccion.rol')
            ->where(['resolucion_dependencias_construccion.id_tramite' => $consulta->id])
            ->select('resolucion_dependencias_construccion.resolucion_status', 'resolucion_dependencias_construccion.created_at', 'resolucion_dependencias_construccion.resolucion_text', 'resolucion_dependencias_construccion.resolucion_archivo', 'users.email', 'roles_construccion.name', 'resolucion_dependencias_construccion.id_licencias_construccion_visor')
            ->orderBy('resolucion_dependencias_construccion.created_at', 'desc')->get();
        $data['revision'] = $revisionesb;
        $data['resolucion'] = $resolucionDependencia;
        if ($role == 4) {
            // $solventac iones = Solventacion::where(['id_tramite' => $consulta->id])->get();
            $solventaciones = DB::table('solventacion_construccion')->join('roles_construccion', 'solventacion_construccion.rol', '=', 'roles_construccion.id')
                ->select('solventacion_construccion.*', 'roles_construccion.name as rolename')->where(['id_tramite' => $consulta->id])->get();
            $data['tramite'] = DB::table('tramite_construccion')->select('aprobado_director')->where('folio', $folio)->first();
            $data['resolucion_revisores'] = DB::table('revisiones_dependencias_construccion')
                ->join('roles_construccion', 'revisiones_dependencias_construccion.rol', '=', 'roles_construccion.id')

                ->select('revisiones_dependencias_construccion.*', 'roles_construccion.name as rolename')
             //   ->where([['id_tramite', '=', $consulta->id], ['rol', '!=', $role]])
             ->where([['id_tramite', '=', $consulta->id]])
                ->get();
            // $data['resolucion_revisores'] = ResolucionDependencias::where([['id_tramite', '=', $consulta->id], ['rol' ,'!=' ,$role]])->get();

            $data['solventacion_construccion'] = $solventaciones;
        } else {
            $solventaciones = SolventacionConstruccion::where(['id_tramite' => $consulta->id, 'rol' => $role])->get();
            $data['solventacion_construccion'] = $solventaciones;
        }

        return $this->successResponse($data);
    }


    public function update(Request $request, $folio)
    {
        // return config('mail');

        // return Mail::sent($m);
        $data = $request->all();
        $folio = base64_decode($folio);


        $resolucion = $data['resolucion'] ?? '';
        $archivo = $data['archivo_url'] ?? '';
        $status_resolucion = $data['status_resolucion'] ?? 0;
        $user = Auth::user();
        $usuarioActual_id = $user->id;
        $role = $user->roles_construccion[0]->id;

        $consulta =  DB::table('consulta_requisitos_construccion')
            ->select('*')
            ->where('folio', $folio)
            ->first();
        if (!$consulta) {
            return $this->errorResponse('No existe folio', 402);
        }
        if (($consulta->id_municipio != $user->id_municipio) || ($role < 2)) {
            return $this->errorResponse('Sin acceso', 403);
        }
        $revisionesb = RevisionesConstruccion::where(['folio' => $folio, 'rol' => $role])->first();
        if (!$revisionesb) {
            return $this->errorResponse('A ocurrido algo intentar más tarde o contactar con el soporte', 500);
        }
        // if ($role == 4 && $status_resolucion == 1 && $archivo == '') {
        //     # code... Orden de pago y notificacion
        //     // $this->notifiacionEmail($folio);
        //     // validar orden de pago
        //     return $this->errorResponse('Adjuntar orden de pago!', 500);
        // }
        if (($revisionesb->status_actual == 3 || $revisionesb->status_actual == null) || ($role == 4 && $revisionesb->status_actual != 1)) {

            $id_array_orden = array();
            $orden = ResolucionConstruccion::where('id_municipio', $user->id_municipio)->orderBy('orden', 'asc')->get();
            foreach($orden as $value2){
                array_push($id_array_orden, $value2->id_rol);
            }



            $resolucionD = new ResolucionConstruccionDependencias();
            $resolucionD->id_tramite = $revisionesb->id_tramite;
            $resolucionD->rol = $role;
            $resolucionD->id_usuario = $usuarioActual_id;
            $resolucionD->resolucion_status = $status_resolucion;
            $resolucionD->resolucion_text = $resolucion;
            $resolucionD->resolucion_archivo = $archivo;
            $resolucionD->save();

            switch ($status_resolucion) {
                case 1:

                    $d = RevisionesConstruccion::where(['folio' => $folio, 'rol' => $role])->update([
                        'fecha_actualizacion' => Carbon::now(),
                        'status_actual' => $status_resolucion,
                        'archivo_actual' => $archivo,
                        'resolver' => false,
                        'id_usuario' => $usuarioActual_id
                    ]);


                    $orden = ResolucionConstruccion::where('id_municipio', $user->id_municipio)->where('tipo_tramite', $consulta->tramite_relacionado)->where('orden', '!=', null)->orderBy('orden', 'asc')->get();

                    $orden_actual = ResolucionConstruccion::where('id_municipio', $user->id_municipio)->where('tipo_tramite', $consulta->tramite_relacionado)->where('id_rol', $role)->first();

                    if($orden_actual->orden <= count($orden)){


                        $orden_actualizar = ResolucionConstruccion::where('id_municipio', $user->id_municipio)->where('tipo_tramite', $consulta->tramite_relacionado)->where('orden', $orden_actual->orden+1)->get();

                        if(count($orden_actualizar)>0){


                            foreach($orden_actualizar as $val5){

                                $d = RevisionesConstruccion::where(['folio' => $folio, 'rol' => $val5->id_rol])->update([
                                    'resolver' => true,
                                ]);

                                if($role != 4){
                                    $nombre_tramite = TipoTramiteConstruccion::where('id', $consulta->tramite_relacionado)->get();
                                    $tipo_tramite = $nombre_tramite[0]->id;
                                    $nombre_tramite = $nombre_tramite[0]->tramite;
                                    $id_rol = $orden_actualizar[0]->id_rol;
                                    $users = Usuario::whereHas('roles_construccion', function ($query) use ($id_rol) {
                                        $query->where('role_id', $id_rol);
                                    })->get();
                                    foreach($users as $user){
                                        Mail::to($user->email)->send(new notificacionRolesConstruccion($nombre_tramite, $folio));
                                    }
                                }
                            }

                        }else{
                            $encontrado = false;
                            for ($i = 1; $i<=20; $i++) {
                                $orden_actualizar = ResolucionConstruccion::where('id_municipio', $user->id_municipio)->where('tipo_tramite', $consulta->tramite_relacionado)->where('orden', $orden_actual->orden+$i)->get();
                                if(count($orden_actualizar)>0){
                                    $encontrado = true;
                                    foreach($orden_actualizar as $val5){
                                        $d = RevisionesConstruccion::where(['folio' => $folio, 'rol' => $val5->id_rol])->update([
                                            'resolver' => true,
                                        ]);
                                    }
                                }
                            }
                        }
                    }

                    # Aprobado
                    $email = '';
                    $tramiteSolv = TramiteConstruccion::where('folio', $folio)->first();
                    $userNotificacion =  $userE->id ?? 0;
                    if ($tramiteSolv->id_usuario != 0 && $tramiteSolv->id_usuario_ventanilla == 0) {
                        $userNotificacion = $tramiteSolv->id_usuario;
                        $email = User::find($tramiteSolv->id_usuario)->id;
                    }
                    if ($role != 4) {
                        $total =   RevisionesConstruccion::where(['folio' => $folio])->get();
                        // return $total;
                        if (count($total) == 1) {
                            $this->insertDirector($revisionesb->id_tramite, $revisionesb->id_municipio, $folio);
                        } elseif (count($total) > 1 && $role != 4) {
                            $todasAprobadas = false;
                            foreach ($total as $key) {
                                $todasAprobadas = $key->status_actual != null ? true : false;
                                if ($todasAprobadas == false) break;
                            }
                            if ($role > 6) {
                                $noti = new NotificacionUserConstruccion;
                                $noti->id_usuario = $userNotificacion;
                                $noti->folio = $folio;
                                $noti->email_solicitante = $email;
                                $noti->comentario = $resolucion;
                                $noti->fecha_creacion = Carbon::now();
                                $noti->archivo_dependencia = $archivo;
                                $noti->notificado = 0;
                                $noti->dependencia_notifica = $role;
                                $noti->type = 1;
                                $noti->save();

                                $this->notifiacionEmail($folio);
                            }
                            if ($todasAprobadas) {
                                $this->insertDirector($revisionesb->id_tramite, $revisionesb->id_municipio, $folio);
                            }
                        }
                    } elseif ($role == 4) {
                        # code... Orden de pago y notificacion
                        $email = $this->tramiteRepository->getNombreSolicitante($folio)['email'];
                        $userE = User::where(['email' => $email])->first();
                        $tramiteSolv = TramiteConstruccion::where('folio', $folio)->first();

                        $noti = new NotificacionUserConstruccion;
                        $noti->id_usuario = $userNotificacion;
                        $noti->folio = $folio;
                        $noti->email_solicitante = $email;
                        $noti->comentario = 'Orden de pago lista para imprimir';
                        $noti->fecha_creacion = Carbon::now();
                        $noti->archivo_dependencia = $archivo;
                        $noti->notificado = 0;
                        $noti->dependencia_notifica = $role;
                        $noti->type = 1;
                        $noti->save();
                        $tramiteUp = TramiteConstruccion::where('folio', $folio)->update(['aprobado_director' => 1]);

                        $this->notifiacionEmail($folio);
                    }
                    break;
                case 2:
                    # Negado
                    //Si el que nega es el revisor que se inserte al director para que lo pueda revizar director tiene la ultima palabra
                    if ($role != 4) {
                        $total =   RevisionesConstruccion::where(['folio' => $folio])->get();
                        if (count($total) > 1 && $role != 4) {
                            $todasAprobadas = false;
                            foreach ($total as $key) {
                                $todasAprobadas = $key->status_actual != null ? true : false;
                                if ($todasAprobadas == false) break;
                            }
                            if ($todasAprobadas) {
                                $this->insertDirector($revisionesb->id_tramite, $revisionesb->id_municipio, $folio);
                            }
                        }
                    }
                    break;
                case 3:
                    # Solventar
                    $tramiteSolv = TramiteConstruccion::where('folio', $folio)->first();
                    $municipio =  MunicipioConstruccion::find($revisionesb->id_municipio);
                    $solventacion = new SolventacionConstruccion();
                    $solventacion->id_tramite = $revisionesb->id_tramite;
                    $solventacion->rol = $role;
                    $solventacion->id_usuario = $usuarioActual_id;
                    // $solventacion->id_usuario_funcionario = $usuarioActual_id ;
                    $solventacion->comentario = $resolucion;
                    $solventacion->fecha_maxima_solventacion = $this->diasHabiles(Carbon::now(), $municipio->dias_solventar);
                    $solventacion->save();

                    $email = $this->tramiteRepository->getNombreSolicitante($folio)['email'];
                    $userE = User::where(['email' => $email])->first();
                    $userNotificacion =  $userE->id ?? 0;
                    if ($tramiteSolv->id_usuario != 0 && $tramiteSolv->id_usuario_ventanilla == 0) {
                        $userNotificacion = $tramiteSolv->id_usuario;
                        $email = User::find($tramiteSolv->id_usuario)->id;
                    }
                    $noti = new NotificacionUserConstruccion;
                    $noti->id_usuario = $userNotificacion;
                    $noti->folio = $folio;
                    $noti->email_solicitante = $email;
                    $noti->comentario = $resolucion;
                    $noti->fecha_creacion = Carbon::now();
                    $noti->archivo_dependencia = $archivo;
                    $noti->id_solventacion = $solventacion->id;
                    $noti->notificado = 0;
                    $noti->dependencia_notifica = $role;
                    $noti->type = 2;
                    $noti->save();

                    $total =   RevisionesConstruccion::where(['folio' => $folio])->get();
                    // return $total;
                    // if(count($total)==1){
                    //    // $this->insertDirector($revisionesb->id_tramite, $revisionesb->id_municipio, $folio);
                    // }else
                    if (count($total) > 1 && $role != 4) {
                        $todasAprobadas = false;
                        foreach ($total as $key) {
                            $todasAprobadas = $key->status_actual != null ? true : false;
                            if ($todasAprobadas == false) break;
                        }
                        if ($todasAprobadas) {
                            $this->insertDirector($revisionesb->id_tramite, $revisionesb->id_municipio, $folio);
                        }
                    }
                    // notificacion es

                    // email
                    $this->notifiacionEmail($folio);
            }
            return $this->successResponse('Actualizado con exito!');
        } else {
            return $this->successResponse('Actualizado con exito!');
        }
    }
    public function updateDir(Request $request, $folio)
    {
        // return config('mail');

        // return Mail::sent($m);
        $data = $request->all();
        $folio = base64_decode($folio);


        $resolucion = $data['resolucion'] ?? '';
        $archivo = $data['archivo_url'] ?? '';
        $status_resolucion = $data['status_resolucion'] ?? 0;
        $user = Auth::user();
        $usuarioActual_id = $user->id;
        $role = $user->roles_construccion[0]->id;
        $tramiteSolv = TramiteConstruccion::where('folio', $folio)->first();


        $consulta =  DB::table('consulta_requisitos_construccion')
            ->select('*')
            ->where('folio', $folio)
            ->first();
        if (!$consulta) {
            return $this->errorResponse('No existe folio', 402);
        }
        if ($consulta->id_municipio != $user->id_municipio && $role == 4) {
            return $this->errorResponse('Sin acceso', 403);
        }
        $revisionesb = RevisionesConstruccion::where(['folio' => $folio, 'rol' => $role])->first();
        if (!$revisionesb) {
            $red = RevisionesConstruccion::where(['folio' => $folio])->get();
            foreach ($red as $key) {
                if ($key->status_actual != 1) {
                    RevisionesConstruccion::where(['folio' => $folio, 'rol' => $key->rol])->update([
                        'fecha_actualizacion' => Carbon::now(),
                        'status_actual' => $status_resolucion + 3,
                        'archivo_actual' => $archivo,
                        'id_usuario' => $usuarioActual_id
                    ]);
                    $resolucionD = new ResolucionConstruccionDependencias;
                    $resolucionD->id_tramite = $key->id_tramite;
                    $resolucionD->rol = $key->rol;
                    $resolucionD->id_usuario = $usuarioActual_id;
                    $resolucionD->resolucion_status = $status_resolucion + 3;
                    $resolucionD->resolucion_text = 'Aprobado director';
                    $resolucionD->resolucion_archivo = $archivo;
                    $resolucionD->save();
                }
            }
            $this->insertDirector($red[0]->id_tramite, $red[0]->id_municipio, $folio);
            $revisionesb = RevisionesConstruccion::where(['folio' => $folio, 'rol' => $role])->first();
        }
        // if ($role == 4 && $status_resolucion == 1 && $archivo == '') {
        //     # code... Orden de pago y notificacion
        //     // $this->notifiacionEmail($folio);
        //     // validar orden de pago
        //     return $this->errorResponse('Adjuntar orden de pago!', 500);
        // }
        if (($revisionesb->status_actual == 3 || $revisionesb->status_actual == null) || ($role == 4 && $revisionesb->status_actual != 1)) {

            $resolucionD = new ResolucionConstruccionDependencias();
            $resolucionD->id_tramite = $revisionesb->id_tramite;
            $resolucionD->rol = $role;
            $resolucionD->id_usuario = $usuarioActual_id;
            $resolucionD->resolucion_status = $status_resolucion;
            $resolucionD->resolucion_text = $resolucion;
            $resolucionD->resolucion_archivo = $archivo;
            $resolucionD->save();
            switch ($status_resolucion) {

                case 1:


                    $d = RevisionesConstruccion::where(['folio' => $folio])->update([
                        'fecha_actualizacion' => Carbon::now(),
                        'status_actual' => $status_resolucion,
                        'archivo_actual' => $archivo,
                        'resolver' => false,
                        'id_usuario' => $usuarioActual_id
                    ]);

                    # code... Orden de pago y notificacion
                    $correo_int = RespuestaConstruccion::where('id_tramite', $consulta->id)->where('name', 'int_correo')->first();
                    $correo_int = $correo_int->value;
                    $email = $correo_int;
                    $userE = User::where(['email' => $correo_int])->first();

                    $userNotificacion =  $userE->id ?? 0;
                    if ($tramiteSolv->id_usuario != 0 && $tramiteSolv->id_usuario_ventanilla == 0) {
                        $userNotificacion = $tramiteSolv->id_usuario;
                        $email = User::find($tramiteSolv->id_usuario)->id;
                    }

                    $tramiteUp = TramiteConstruccion::where('folio', $folio)->update(['aprobado_director' => 1]);
                    if($tramiteSolv->pdf_licencia == ''){
                        $noti = new NotificacionUserConstruccion();
                        $noti->id_usuario = $userNotificacion;
                        $noti->folio = $folio;
                        $noti->email_solicitante = $email;
                        $noti->comentario = 'Orden de pago lista para imprimir';
                        $noti->fecha_creacion = Carbon::now();
                        $noti->archivo_dependencia = $archivo;
                        $noti->notificado = 0;
                        $noti->dependencia_notifica = $role;
                        $noti->type = 1;
                        $noti->save();
                        //$this->notifiacionEmail($folio);
                    }


                    break;
                case 2:

                    # Negado
                    //Si el que nega es el revisor que se inserte al director para que lo pueda revizar director tiene la ultima palabra
                    // if ($role != 4) {
                    //     $total =   RevisionesConstruccion::where(['folio' => $folio])->get();
                    //     if (count($total) > 1 && $role != 4) {
                    //         $todasAprobadas = false;
                    //         foreach ($total as $key) {
                    //             $todasAprobadas = $key->status_actual != null ? true : false;
                    //             if ($todasAprobadas == false) break;
                    //         }
                    //         if ($todasAprobadas) {
                    //             $this->insertDirector($revisionesb->id_tramite, $revisionesb->id_municipio, $folio);
                    //         }
                    //     }
                    // }
                    break;
                case 3:
                    # Solventar
                    $tramiteSolv = TramiteConstruccion::where('folio', $folio)->first();
                    $municipio =  MunicipioConstruccion::find($revisionesb->id_municipio);
                    $solventacion = new SolventacionConstruccion();
                    $solventacion->id_tramite = $revisionesb->id_tramite;
                    $solventacion->rol = $role;
                    $solventacion->id_usuario = $usuarioActual_id;
                    // $solventacion->id_usuario_funcionario = $usuarioActual_id ;
                    $solventacion->comentario = $resolucion;
                    $solventacion->fecha_maxima_solventacion = $this->diasHabiles(Carbon::now(), $municipio->dias_solventar);
                    $solventacion->save();

                    $correo_int = RespuestaConstruccion::where('id_tramite', $consulta->id)->where('name', 'int_correo')->first();
                    $correo_int = $correo_int->value;
                    $email = $correo_int;
                    $userE = User::where(['email' => $correo_int])->first();
                    $userNotificacion =  $userE->id ?? 0;
                    if ($tramiteSolv->id_usuario != 0 && $tramiteSolv->id_usuario_ventanilla == 0) {
                        $userNotificacion = $tramiteSolv->id_usuario;
                        $email = User::find($tramiteSolv->id_usuario)->id;
                    }
                    $noti = new NotificacionUserConstruccion;
                    $noti->id_usuario = $userNotificacion;
                    $noti->folio = $folio;
                    $noti->email_solicitante = $email;
                    $noti->comentario = $resolucion;
                    $noti->fecha_creacion = Carbon::now();
                    $noti->archivo_dependencia = $archivo;
                    $noti->id_solventacion = $solventacion->id;
                    $noti->notificado = 0;
                    $noti->dependencia_notifica = $role;
                    $noti->type = 2;
                    $noti->save();

                    $total =   RevisionesConstruccion::where(['folio' => $folio])->get();
                    // return $total;
                    // if(count($total)==1){
                    //    // $this->insertDirector($revisionesb->id_tramite, $revisionesb->id_municipio, $folio);
                    // }else
                    // if (count($total) > 1 && $role != 4) {
                    //     $todasAprobadas = false;
                    //     foreach ($total as $key) {
                    //         $todasAprobadas = $key->status_actual != null ? true : false;
                    //         if ($todasAprobadas == false) break;
                    //     }
                    //     if ($todasAprobadas) {
                    //         $this->insertDirector($revisionesb->id_tramite, $revisionesb->id_municipio, $folio);
                    //     }
                    // }
                    // notificacion es

                    // email
                    Mail::to($correo_int)->send(new notificacionPrevencionConstruccion($folio));
            }

            $datos_ = $request->all();
            $data_2 = collect($datos_);
            $valor_anterior = $folio;
            $this->historialConstruccion($accion = 'Se elaboro una resolución', $valor_anterior, $data_2, $tipo = 2, 0);

            return $this->successResponse('Actualizado con exito!');
        } else {
            return $this->successResponse('Actualizado con exito!');
        }
    }

    public function insertDirector($id_tramite, $id_municipio, $folio)
    {
        $get = RevisionesConstruccion::where(['folio' => $folio, 'rol' => 4])->first();

        if ($get) {
            return false;
        }
        $revisionDirector = new RevisionesConstruccion();
        $revisionDirector->id_tramite = $id_tramite;
        $revisionDirector->id_municipio = $id_municipio;
        $revisionDirector->folio = $folio;
        $revisionDirector->rol = 4;
        $revisionDirector->fecha_inicio = Carbon::now();
        $revisionDirector->save();
        return $revisionDirector;
    }

    public function  diasHabiles($fecha, $dias)
    {
        $datestart = strtotime($fecha);
        $datesuma = 15 * 86400;
        $diasemana = date('N', $datestart);
        $totaldias = $diasemana + $dias;
        $findesemana = intval($totaldias / 5) * 2;
        $diasabado = $totaldias % 5;
        if ($diasabado == 6) $findesemana++;
        if ($diasabado == 0) $findesemana = $findesemana - 2;
        $total = (($dias + $findesemana) * 86400) + $datestart;
        return $fechafinal = date('Y-m-d', $total);
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

    public function getFlujoResolucion($folio){


        $user = Auth::user();

        $folio = base64_decode($folio);

        $data = RevisionesConstruccion::where('folio', $folio)->where('id_municipio', $user->id_municipio)->orderBy('id', 'asc')->get();
        $dataNew = array();
        foreach($data as $value){
            $rolTemp = RoleConstruccion::where('id', $value->rol)->first();
            $value->rol_name = $rolTemp->name;
            $res = ResolucionConstruccionDependencias::where('id_tramite', $value->id_tramite)->where('rol', $value->rol)->where('resolucion_status', 1)->first();
            if($res){
                $value->resolver = true;
            }else{
                $value->resolver = false;
            }
            array_push($dataNew, $value);
        }

        if($dataNew){
            return $this->successResponse($dataNew,202);
        } else{
            return $this->errorResponse('Error', 402);
        }

    }



    public function emitirLicencia(Request $request, $folio)
    {

        $logo = $this->getImage($folio);
        $folio            = base64_decode($folio);
        $user = Auth::user();
        $usuarioActual_id = $user->id;
        $role = $user->roles_construccion[0]->id;
        if (!$role) {
            return $this->errorResponse('Este usuario no tiene asignado un role', 403);
        }
        if ($role != 4) {
            return $this->errorResponse('Sin permiso', 403);
        }

        $query2 =  DB::table('consulta_requisitos_construccion')
            ->select('*')
            ->where('folio', $folio)
            ->get();
        $st2 = isset($query2[0]) ? $query2[0] : false;
        if (!$st2) {
            return $this->errorResponse('No se puedo encontrar el folio', 401);
        }
        if ($query2[0]->id_municipio != $user->id_municipio) {
            return $this->errorResponse('No se puedo encontrar el folio', 403);
        }

        $usuarioFolio = $query2[0]->id_usuario;
        $idConsulta = $query2[0]->id;


        if ($request->file('licencia')) {
            $fecha1 = Carbon::now()->format('Y-m-d-H-i-s');
            $filename = $request->licencia->getClientOriginalName();
            $filename2 = explode('.', $filename);
            //crear una carpeta por cada tramite
            $fileNew =  'licencia-' . str_replace('/', '-', $folio) . '-' . $fecha1 . '.' . $filename2[1];
            $destinationPath = 'public/licencias_construccion_file/' . $idConsulta . '/lic';
            $final = $request->licencia->move(base_path("$destinationPath"), $fileNew);
            $fileInsert = str_replace('public/', '', $destinationPath) . '/' . $fileNew;
            // return $final;
            $t = TramiteConstruccion::where('folio', $folio)->update(['pdf_licencia' => $fileInsert]);
            $tramiteSolv = TramiteConstruccion::where('folio', $folio)->first();
            $this->notifiacionEmail($folio);
            $email = $this->tramiteRepository->getNombreSolicitante($folio)['email'];

            /** Se genera la licencia de visor jalisco pero se sube la de ellos **/
            $duenoData = $this->tramiteRepository->getDataDueno($folio);
            $curp_proprietario = '';

            $muni = DB::table('municipios_construccion')
                ->where('id', $query2[0]->id_municipio)
                ->first();
            $firma = $muni->firma_director == null ? '' : $muni->firma_director;
            $lic = LicenciaConstruccionVisor::where(['folio' => $folio, 'anio_licencia' => date('Y')])->get();
            if (count($lic) == 0) {
                $firmas = FirmaMunicipioConstruccion::where(['id_municipio' => $query2[0]->id_municipio])->orderBy('orden', 'asc')->get();
                foreach ($firmas as $value) {
                    $firmasF[$value->orden] = $value;
                }

                $cout_lics = LicenciaConstruccionVisor::where(['municipio_id' => $query2[0]->id_municipio])->count();
                $currentUser = Auth::user();
                $lic = LicenciaConstruccionVisor::create([
                    'folio' => $folio,
                    'actividad_comercial' => $query2[0]->nombre_scian,
                    'codigo_scian' => $query2[0]->codigo_scian,
                    'superficie_autorizada' => $query2[0]->superficie_actividad,
                    'dueno' => $duenoData['nombre'],
                    'apellido_p' => $duenoData['apellido_p'],
                    'apellido_m' => $duenoData['apellido_m'],
                    'caracter' => $duenoData['caracter'],
                    'curp' => $duenoData['curp'],
                    'hora_a' => '',
                    'hora_c' => '',
                    'img_logo' => $logo,
                    'firma' => $firma,
                    'url_minimapa' => $query2[0]->url_minimapa,
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
                    'pdf_escaneado' => env('APP_URL') . $fileInsert,
                    'municipio_id' => $query2[0]->id_municipio,
                    'numero_lic' => $cout_lics + 1,
                    'consecutivo' => ($cout_lics + 1) . '/' . date('Y'),
                ]);
            }
            /******/


            $userE = User::where(['email' => $email])->first();
            $userNotificacion =  $userE->id ?? 0;
            if ($tramiteSolv->id_usuario != 0 && $tramiteSolv->id_usuario_ventanilla == 0) {
                $userNotificacion = $tramiteSolv->id_usuario;
                $email = User::find($tramiteSolv->id_usuario)->id;
            }
            $noti = new NotificacionUserConstruccion;
            $noti->id_usuario = $userNotificacion;
            $noti->folio = $folio;
            // $noti->id_tramite = 0;
            $noti->email_solicitante = $email;
            $noti->comentario = 'Licencia emitida!!';
            $noti->fecha_creacion = Carbon::now();
            $noti->archivo_dependencia = env('APP_URL') . $fileInsert;
            $noti->notificado = 0;
            $noti->type = 1;
            $noti->dependencia_notifica = $role;
            $noti->save();

            $datos_            = $request->all();
            $data_2 = collect($datos_);
            $valor_anterior = $folio;
            $this->historialConstruccion($accion = 'Se emitio una licencia', $valor_anterior, $data_2, $tipo = 5, 0);

            return $this->successResponse(
                $fileInsert,
                202
            );
        } else {
            return $this->errorResponse('Adjuntar archivo', 402);
        }
    }
    public function uploadOrdenPago(Request $request, $id)
    {
        $id            = base64_decode($id);
        $user = Auth::user();
        $usuarioActual_id = $user->id;
        $role = $user->roles_construccion[0]->id;
        if (!$role) {
            return $this->errorResponse('Este usuario no tiene asignado un role', 403);
        }
        //   if ($role!=4) {
        //     return $this->errorResponse('Sin permiso', 403);
        //   }
        $query2 =  DB::table('licencias_construccion_visor')
            ->select('*')
            ->where('id', $id)
            ->get();

        $st2 = isset($query2[0]) ? $query2[0] : false;
        if (!$st2) {
            return $this->errorResponse('No se puedo encontrar el folio', 401);
        }
        if ($query2[0]->municipio_id != $user->id_municipio && $role == 4) {
            return $this->errorResponse('No se puedo encontrar el folio', 403);
        }
        $idConsulta = $query2[0]->id;
        $folio = $query2[0]->folio;

        $datos2            = $request->all();
        $data2 = collect($datos2);
        $valor_anterior = '';
        $this->historialConstruccion($accion = 'Se subio orden de pago', $valor_anterior, $data2, $tipo = 3, 0);

        if ($request->file('orden')) {
            $fecha1 = Carbon::now()->format('Y-m-d-H-i-s');
            $filename = $request->orden->getClientOriginalName();
            $filename2 = explode('.', $filename);
            //crear una carpeta por cada tramite
            $fileNew =  'ordenpago-' . str_replace('/', '-', $id) . '-' . $fecha1 . '.' . $filename2[1];
            $destinationPath = 'public/licencias_construccion_file/' . $idConsulta . '/lic';
            $final = $request->orden->move(base_path("$destinationPath"), $fileNew);
            $fileInsert = str_replace('public/', '', $destinationPath) . '/' . $fileNew;
            // return $final;
            $t = LicenciaConstruccionVisor::where('id', $id)->update(['archivo_compobante_pago' => $fileInsert]);
            $this->notifiacionEmail($folio);
            $tramiteSolv = TramiteConstruccion::where('folio', $folio)->first();
            $this->notifiacionEmail($folio);
            $email = $this->tramiteRepository->getNombreSolicitante($folio)['email'];
            $userE = User::where(['email' => $email])->first();
            $userNotificacion =  $userE->id ?? 0;
            if ($tramiteSolv->id_usuario != 0 && $tramiteSolv->id_usuario_ventanilla == 0) {
                $userNotificacion = $tramiteSolv->id_usuario;
                $email = User::find($tramiteSolv->id_usuario)->id;
            }
            return $this->successResponse(
                $fileInsert,
                202
            );
        } else {
            return $this->errorResponse('Adjuntar archivo', 402);
        }
    }
    public function getImage($folio)
    {

        $folio  = base64_decode($folio);
        $data = DB::table('municipios_construccion')
            ->join('consulta_requisitos_construccion', 'consulta_requisitos_construccion.municipio', 'municipios_construccion.nombre')
            ->select('image')
            ->where('consulta_requisitos_construccion.folio', $folio)
            ->get();

        $image =  $data[0]->image;

        if ($image === NULL) {
            $logo = "https://cuernavaca.visorurbano.com/assets/images/logo_cuernavaca.svg";
            return $logo;
        } else {
            $logo = $image;
            return $logo;
        }
    }
}
