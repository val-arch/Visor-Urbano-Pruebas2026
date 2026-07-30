<?php
namespace App\Repositories;

use Exception;
use App\Models\Municipio;
use App\Models\Solventacion;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;

use App\Repositories\Interfaces\INotificacionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificacionRepository implements INotificacionRepository
{

    /**
     * @param string $id
     * @param $files
     */
    public function image($id, $files): void
    {
        $entry = Solventacion::where('id',$id)->first();
        $nombreArchivos = null;
        $usuarioActualId = Auth::user()->id;
        $role = Auth::user()->roles[0]->id;
        foreach($files as $file){
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $path     = app()->basePath('public/licencias_giro_file/'.$id.'/solventaion/');

            $file->move($path, $filename);
            if($nombreArchivos == null){
                //$nombreArchivos = URL::to('licencias_giro_file/'.$id.'/solventaion/' . $filename);
                $nombreArchivos = ('licencias_giro_file/'.$id.'/solventaion/' . $filename);
            }else{
                //$nombreArchivos = $nombreArchivos.'|'.URL::to('licencias_giro_file/'.$id.'/solventaion/' . $filename);
                $nombreArchivos = $nombreArchivos.'|'.('licencias_giro_file/'.$id.'/solventaion/' . $filename);
            }
        }
        if ($entry) {
            $entry->archivos =   $nombreArchivos;
            $entry->save();
        } else {
            $entry              = new Solventacion();
            $entry->id_tramite  = $id;
            $entry->rol         = $role;
            $entry->id_usuario  = $usuarioActualId;
            $entry->archivos =  $nombreArchivos;
            $entry->save();
       }
    }
    public function destroy(int $id): void
    {
        Municipio::destroy($id);
    }
}
