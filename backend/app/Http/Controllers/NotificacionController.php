<?php

namespace App\Http\Controllers;

use App\Models\Solventacion;
use App\Repositories\Interfaces\INotificacionRepository;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Traits\LogHistorico;
use Illuminate\Support\Facades\Auth;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\NotificacionUser;
use App\Models\Tramite;
use App\Repositories\Interfaces\ITramiteRepository;





class NotificacionController extends Controller
{
    use ApiResponser;
    use LogHistorico;
    public INotificacionRepository $notificacionRepository;
    private ITramiteRepository $tramiteRepository;


    public function __construct(INotificacionRepository $notificacionRepository,ITramiteRepository $tramiteRepository)
    {
        $this->notificacionRepository = $notificacionRepository;
        $this->tramiteRepository = $tramiteRepository;
    }
 

    public function updateFile(int $id,Request $request)
    { 
        $entry = Solventacion::where('id_tramite',$id)->first();
        if ($entry) {
            $entry->archivos  = $request->ruta;
            $entry->save();
        }
        $data2          = json_encode($request->except(['token']));   
        $this->historial($accion='Se actualizo  archivo solventacion','',$data2,$tipo=3,0);
   
        return $this->successResponse(
            'Cambiado Correctamente',
            202
        );
    }

    public function updateNotificacion(Request $request, int $id)
    {
        $data = DB::table('notificaciones')
                   ->join('tramite','tramite.folio','notificaciones.folio')
                   ->select('notificaciones.id as id', 'tramite.id_usuario as user', 'tramite.folio as folio')
                   ->where('notificaciones.id', $id)
                   ->get();

        $user = $data[0]->user;
        $folio = $data[0]->folio;
        $usuarioActualId = Auth::user()->id;
    
        $data2          = json_encode($request->except(['token']));   
        $this->historial($accion='Se actualizo  notificacion','',$data2,$tipo=3,0);
   
        if($user === null || $user == 0 ){
           $data = Tramite::where('folio', $folio)->update(['id_usuario' =>$usuarioActualId]);
           $notificacion = DB::table('notificaciones')
            ->where('id', $id)
            ->update(['notificado' => 1, 'fecha_visto' => Carbon::now(),'id_usuario'=>$usuarioActualId]);
            return $this->successResponse(
                'Cambiado Correctamente',
                202
            );
        }
        $notificacion = DB::table('notificaciones')
            ->where('id', $id)
            ->update(['notificado' => 1, 'fecha_visto' => Carbon::now()]);

        return $this->successResponse(
            'Cambiado Correctamente',
            202
        );
    }


    function getNotificaciones(){
     
        $usuarioActualId = Auth::user()->id;
        $email = Auth::user()->email;

        $data = DB::table('consulta_requisitos')
        ->join('notificaciones','consulta_requisitos.folio','=','notificaciones.folio')
        ->select('notificaciones.id as id','consulta_requisitos.folio as folio','consulta_requisitos.municipio as municipio','notificaciones.notificado as estado','notificaciones.type as tipo','notificaciones.comentario as mensaje','notificaciones.archivo_dependencia',DB::raw('DATE(notificaciones.fecha_creacion) as fecha'))
        ->where('notificaciones.id_usuario', '=', $usuarioActualId)
        ->Orwhere('notificaciones.email_solicitante','=', $email)
        ->orderBy('id','desc')
        ->paginate(20);
        if($data == false){
            return $this->errorResponse('No tienes permiso', 403);  
        }else{
            return $this->successResponse($data);
        }
    }

    function getNotificacion(int $id){
        $user = Auth::user();
        $usuarioActualId = $user->id;
        $role = $user->roles[0]->id;
        $data = DB::table('consulta_requisitos')
        ->join('notificaciones','consulta_requisitos.folio','=','notificaciones.folio')
        ->select('notificaciones.id as id','consulta_requisitos.folio as folio','consulta_requisitos.municipio as municipio','notificaciones.notificado as estado','notificaciones.archivo_dependencia as pdf','notificaciones.id_solventacion','notificaciones.type as tipo','notificaciones.comentario as mensaje',DB::raw('DATE(notificaciones.fecha_creacion) as fecha'));
        if($role == 1){
           $data = $data->where('notificaciones.id_usuario', '=', $usuarioActualId);
        }
        
        $data = $data->where('notificaciones.id',$id)
        ->paginate(20);
       
        if($data == false){
            return $this->errorResponse('No tienes permiso', 404);  
        }else{
            return $this->successResponse($data);
          
        }
    }
    function getFilesSolventacion(int $id){
        //$not =  NotificacionUser::find($id);
        $data = DB::table('solventacion')
        ->select('archivos')
         ->where('id',$id)
         ->first();
        if($data == false){
            return $this->errorResponse('No tienes permiso', 404);  
        }else{
            return $this->successResponse($data);
        }
    }
    public function  uploadFile(int $id, Request $request)
    {
        $currentUser = Auth::user();
        $files       = $request->all()['image'];
        $this->notificacionRepository->image(
            $id,
            $files
        );
        $data2          = json_encode($request->except(['token']));   
        $this->historial($accion='Se actualizo un archivo notificacion','',$data2,$tipo=3,0);
        return $this->successResponse(
            'Actualizado Correctamente',
            202
        );
    }

    public function update(int $id, Request $request)
    {
        $usuarioActualId = Auth::user()->id;
        $role = Auth::user()->roles[0]->id;
        $entry = Solventacion::where('id',$id)->first();
        if (!is_null($entry)) {
            $entry->comentario_usuario       = $request->comentario;
            $entry->save();
        } else {
            $entry              = new Solventacion();
            $entry->id_tramite  = $id;
            $entry->rol         = $role;
            $entry->id_usuario  = $usuarioActualId;
            $entry->comentario_usuario  = $request->comentario;
            $entry->save();
        }
        $data2   = json_encode($request->except(['token']));   
        $this->historial($accion='Se actualizo una solventacion','',$data2,$tipo=3,0);
        return $this->successResponse(
            'Actualizado Correctamente',
            202
        );
    }
}
