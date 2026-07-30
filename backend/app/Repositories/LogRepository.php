<?php
namespace App\Repositories;

use App\Models\LogGiro;
use App\Repositories\Interfaces\ILogs;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class LogRepository implements ILogs
{
  /*  public function historial(string $accion, string $anterior, int $id_usuario, int $tipo_log, int $id_tramite,  int $id_role,string $post_request)
    {

        $equipo      = $_SERVER['HTTP_USER_AGENT'];
        $host        = $_SERVER['HTTP_HOST'];
        $ip_user     = $_SERVER['REMOTE_ADDR'];

        $logGrio                = new LogGiro();
        $logGrio->accion        = $accion; 
        $logGrio->anterior      = $anterior; 
        $logGrio->id_usuario    = $id_usuario; 
        $logGrio->tipo_log      = $tipo_log; 
        $logGrio->id_tramite    = $id_tramite; 
        $logGrio->host          = $host; 
        $logGrio->ip_user       = $ip_user; 
        $logGrio->id_role       = $id_role; 
        $logGrio->equipo        = $equipo; 
        $logGrio->post_request  = $post_request; 
        $logGrio->save();
    }*/  

    public function paginate(int $take)
    {
        if($take == 0){
            return LogGiro::orderBy('id')
            ->get();
        }else{
            return LogGiro::orderBy('id')->where('tipo_log',$take)
            ->get();
        }
      
    }
   
    public function find(int $id): ? Log
    {
        return LogGiro::find($id);
    } 
}