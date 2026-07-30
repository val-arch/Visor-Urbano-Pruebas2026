<?php

namespace App\Traits;

use App\Models\LogConstruccion;
use App\Repositories\Interfaces\ILogs;
use Illuminate\Support\Facades\Auth;

trait LogHistoricoConstruccion{

    public function historialConstruccion($accion,$anterior,$post_requiest,$tipo,$id_tramite){
        $currentUser = Auth::user();
        if( isset($currenUster->id))
        {
            $id_usuario =  $currentUser->id;
            $id_role    =   $currentUser->roles_construccion[0]->id;
        }else{
            $id_usuario  = 0;
            $id_role     = 0;
        }
        $equipo      = $_SERVER['HTTP_USER_AGENT'];
        $host        = $_SERVER['HTTP_HOST'];
        $ip_user     = $_SERVER['REMOTE_ADDR'];

        $logConstruccion                = new LogConstruccion();
        $logConstruccion->accion        = $accion; 
        $logConstruccion->anterior      = $anterior; 
        $logConstruccion->id_usuario    = $id_usuario; 
        $logConstruccion->tipo_log      = $tipo; 
        $logConstruccion->id_tramite    = $id_tramite; 
        $logConstruccion->host          = $host; 
        $logConstruccion->ip_user       = $ip_user; 
        $logConstruccion->id_role       = $id_role; 
        $logConstruccion->equipo        = $equipo; 
        $logConstruccion->post_request  = $post_requiest; 
        $logConstruccion->save();
    }
  
}