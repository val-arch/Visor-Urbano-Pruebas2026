<?php

namespace App\Traits;

use App\Models\LogGiro;
use App\Repositories\Interfaces\ILogs;
use Illuminate\Support\Facades\Auth;

trait LogHistorico{

    public function historial($accion,$anterior,$post_requiest,$tipo,$id_tramite){
        $currentUser = Auth::user();
        if( isset($currentUser->id))
        {
            $id_usuario =  $currentUser->id;
            $id_role    =   $currentUser->roles[0]->id;
        }else{
            $id_usuario  = 0;
            $id_role     = 0;
        }
  
        $equipo      = $_SERVER['HTTP_USER_AGENT'];
        $host        = $_SERVER['HTTP_HOST'];
        $ip_user     = $_SERVER['REMOTE_ADDR'];

        $logGrio                = new LogGiro();
        $logGrio->accion        = $accion; 
        $logGrio->anterior      = $anterior; 
        $logGrio->id_usuario    = $id_usuario; 
        $logGrio->tipo_log      = $tipo; 
        $logGrio->id_tramite    = $id_tramite; 
        $logGrio->host          = $host; 
        $logGrio->ip_user       = $ip_user; 
        $logGrio->id_role       = $id_role; 
        $logGrio->equipo        = $equipo; 
        $logGrio->post_request  = $post_requiest; 
        $logGrio->save();
    }
  
}

