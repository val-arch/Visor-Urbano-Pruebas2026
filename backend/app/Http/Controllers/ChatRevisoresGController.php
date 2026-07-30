<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Role;
use App\Models\UserRole;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;




class ChatRevisoresGController extends Controller{
    use ApiResponser;
    

    public function getChat($folio){

        $usuarioActual_id = Auth::user()->id;
        $id_role = self::getRole($usuarioActual_id);
       
        if($id_role > 1){
            $chats = DB::table('chat_revisores')
                   ->select('*')
                   ->join('consulta_requisitos','consulta_requisitos.id', '=', 'chat_revisores.id_tramite')
                   ->where('folio',$folio)
                   ->get();
       
            return response()->json($chats, 200);
     
        }else{
            return $this->errorResponse('No tienes permiso', 404);      

        }
      
    }

    public function getRole( $usuarioActual_id){
        $query = User::select('user_roles.id','user_roles.role_id')
        ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
        ->where('user_id', '=',  $usuarioActual_id)
        ->get();

        $role = $query[0]->role_id;

        return $role;
    }
    
    public function getIdTramite($folio){

        $query2 = DB::table('consulta_requisitos')
                    ->where('consulta_requisitos.folio', '=', $folio )
                    ->get();
        return $query2;

    }

    public function insertChat(Request $request, $folio){

        $query2 = DB::table('consulta_requisitos')
        ->where('consulta_requisitos.folio', '=', $folio )
        ->get();

        $id_tramite = $query2[0]->id;

        $usuarioActual_id = Auth::user()->id;
        $id_role = self::getRole($usuarioActual_id);

        $archivo = $request->file('archivo');    
        $finalArchivo = self::uploadFile($archivo, $folio);           
        $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 
                            'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 
                            'xbm', 'xpm', 'xwd'];

        $explodeImage = explode('.', $finalArchivo);
        $extension = end($explodeImage);

        if(in_array($extension, $imageExtensions))  {
            //El archivo es una imagen    
            $data =  array(
                'id_tramite'      => $id_tramite,
                'ir_usuario'      => $usuarioActual_id,
                'rol'             => $id_role,
                'comentario'      => $request->input('comentario'),
                'imagen'          => $finalArchivo,
                'created_at' => Carbon::now(),
        
             );
        }else if($extension == "pdf"){
            //El archivo es un pdf    
            $data =  array(
                'id_tramite'      => $id_tramite,
                'ir_usuario'      => $usuarioActual_id,
                'rol'             => $id_role,
                'comentario'      => $request->input('comentario'),
                'archivo_adjunto' => $finalArchivo,
                'created_at' => Carbon::now(),
        
             );
        }else{
            
        return $this->errorResponse('Archivo no permitido', 404);      

        }
       
        DB::table('chat_revisores')->insert($data);
        return $this->successResponse(
            "success",202
        );
    }


  public function  uploadFile($file, $folio){
       $usuarioActual_id = Auth::user()->id;
       $id_role = self::getRole($usuarioActual_id);
       $fecha1 = Carbon::now()->format('Y-m-d-H-i-s');
       $filename = $file->getClientOriginalName();
       $filename2 = explode('.', $filename);
       $fileNew =  $filename2[0].'_'.$fecha1.'.'. $filename2[1];
       $destinationPath = 'public/licencias_giro/'.$folio.'/'.$id_role;
       $final = $file->storeAs("$destinationPath",$fileNew);

       return $final;
   }

}
