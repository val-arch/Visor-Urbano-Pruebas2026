<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use App\Traits\LogHistoricoConstruccion;
use App\Models\Tramite;
use App\Repositories\Interfaces\ILogs;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator, Redirect, Response;
use Illuminate\Support\Facades\Auth;

class UploadConstruccionController extends Controller
{
  use ApiResponser;
  use LogHistoricoConstruccion;
  

 

  public function upload(Request $request, $name)
  {


    $folio            = base64_decode($request->all()['folio']);
    $datos            = $request->all(); 
  
    $usuarioActual_id = Auth::user()->id;
    $step_actual = '';
    $role = Auth::user()->roles_construccion[0]->id;
      if (!$role) {
        return $this->errorResponse('Este usuario no tiene asignado un role', 404);
      }
      $data2 = collect($datos);
      $valor_anterior = $step_actual;
      $this->historialConstruccion($accion='Se subio la licencia de giro',$valor_anterior,$data2,$tipo=2,0);
      $query2 =  DB::table('consulta_requisitos_construccion')
                    ->select('*')
                    ->where('folio', $folio)
                    ->get();
      $st2 = isset($query2[0]) ? $query2[0] : false;
      if (!$st2) {
        return $this->errorResponse('No se puedo encontrar el folio', 404);
      }

    $usuarioFolio = $query2[0]->id_usuario;
    $idConsulta = $query2[0]->id;
    $respuesta =  DB::table('respuestas_construccion')
                    ->select('*')
                    ->where('name', '=', $name)
                    ->where('id_tramite', '=', $idConsulta)
                    ->first();
   
    $validator = Validator::make(
      $request->all(),
      [
          'file' =>"required|mimetypes:application/dwg|max:50000",
          'file' =>"required|mimetypes:application/pdf|max:5000"
      ]
    );
    if ($role > 1 || isset($usuarioFolio) && ($role == 1 && $usuarioActual_id == $usuarioFolio)) {
      if ($request->file('file')) { 
       //  dd( count($request->file('file')) );
        if (is_array($request->file('file')) && count($request->file('file')) > 1) {
          $fileData = [];
          foreach($request->file('file') as $file){
            $fecha1 = Carbon::now()->format('Y-m-d-H-i-s');
            $filename = $file->getClientOriginalName();
            $filename2 = explode('.', $filename);
            $fileNew =  $filename2[0] . '_visor' . $fecha1 . '.' . $filename2[1];
            $destinationPath = 'public/licencias_construccion_file/' . $idConsulta;
            $final = $file->move(base_path("$destinationPath"), $fileNew);
            $fileInsert = str_replace('public/','',$destinationPath).'/'.$fileNew;
            $fileData[] = $fileInsert; 
          }     
          $fileData = json_encode($fileData);
          $fileData= str_replace(',', '|',  $fileData);
          $fileData = str_replace(array('[',']','\\' ,'"'), '', $fileData);

          $data =  array(
              'value'       => $fileData,
              'name'       => $name,
              'id_usuario' => $usuarioActual_id,
              'updated_at' => Carbon::now()
          );
        
            $valor = array(
              'name' => $name,
              'value' => $fileData,
            );
            $data2 =  array(
              'id_tramite' => $idConsulta,
              'respuestas' => json_encode($valor),
              'id_usuario' => $usuarioActual_id,
              'created_at' => Carbon::now(),
              'updated_at' => Carbon::now()
            );
            if ($respuesta === null) {
              DB::table('respuestas_construccion')->insert($data);
            } else {
              DB::table('respuestas_construccion')->where('name', '=', $name)
                ->where('id_tramite', '=', $idConsulta)->update($data);
              DB::table('respuestas_construccion_json')
                ->where('respuestas->name', '=', $name)
                ->where('id_tramite', '=', $idConsulta)->update($data2);
            }
            return $this->successResponse(
              "success",
              202
            );
      }else{


          $fecha1 = Carbon::now()->format('Y-m-d-H-i-s');
          $filename = $request->file->getClientOriginalName();
          $filename2 = explode('.', $filename);
          //crear una carpeta por cada tramite
          $fileNew =  $filename2[0] . '_visor' . $fecha1 . '.' . $filename2[1];
          $destinationPath = 'public/licencias_construccion_file/' . $idConsulta;
          $final = $request->file->move(base_path("$destinationPath"), $fileNew);
          $fileInsert = str_replace('public/','',$destinationPath).'/'.$fileNew;
         // dd($fileInsert);
        
          $data =  array(
            'id_tramite' => $idConsulta,
            'name' => $name,
            'value' => $fileInsert,
            'id_usuario' => $usuarioActual_id,
            //'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
          );
          $valor = array(
            'name' => $name,
            'value' => $fileInsert,
          );
          $data2 =  array(
            'id_tramite' => $idConsulta,
            'respuestas' => json_encode($valor),
            'id_usuario' => $usuarioActual_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
          );
        
          if ($respuesta === null) {
            DB::table('respuestas_construccion')->insert($data);
          //  DB::table('respuestas_json')->insert($data2);
          } else {
            DB::table('respuestas_construccion')->where('name', '=', $name)
              ->where('id_tramite', '=', $idConsulta)->update($data);

            DB::table('respuestas_construccion_json')
              ->where('respuestas->name', '=', $name)
              ->where('id_tramite', '=', $idConsulta)->update($data2);
          }
          return $this->successResponse(
            "success",
            202
          );
            
      }
    }else{


    }
    } else {
      //return 'no tienes permiso';
      return $this->errorResponse('No tienes permiso', 402);
    }
  }

  public function uploadFile(Request $request, $folio)
  {
    $folio            = base64_decode($folio);
    $carta = $request->all()['carta'];
    $usuarioActual_id = Auth::user()->id;
    $role = Auth::user()->roles_construccion[0]->id;
      if (!$role) {
        return $this->errorResponse('Este usuario no tiene asignado un role', 401);
      }
    
    $query2 =  DB::table('consulta_requisitos_construccion')
                    ->select('*')
                    ->where('folio', $folio)
                    ->get();
      $st2 = isset($query2[0]) ? $query2[0] : false;
      if (!$st2) {
        return $this->errorResponse('No se puedo encontrar el folio', 401);
      }

    $usuarioFolio = $query2[0]->id_usuario;
    $idConsulta = $query2[0]->id;
   
    $validator = Validator::make(
      $request->all(),
      [
       // 'file' => 'required|mimes:doc,docx,pdf,txt|max:2048',
          'file' =>"required|mimetypes:application/pdf|max:2048"
      ]
    );
    if ($role > 1 || isset($usuarioFolio) && ($role == 1 && $usuarioActual_id == $usuarioFolio)) {

      if ($request->file('file')) {       
        $fecha1 = Carbon::now()->format('Y-m-d-H-i-s');
        $filename = $request->file->getClientOriginalName();
        $filename2 = explode('.', $filename);
        //crear una carpeta por cada tramite
        $fileNew =  $filename2[0] . '_visor' . $fecha1 . '.' . $filename2[1];
        $destinationPath = 'public/licencias_construccion_file/' . $idConsulta;
        $final = $request->file->move(base_path("$destinationPath"), $fileNew);
        $fileInsert = str_replace('public/','',$destinationPath).'/'.$fileNew;
       // return $final;
       if($carta=="true" && $role > 1){
        $t = Tramite::where('folio',$folio)->update(['carta_responsiva'=>$fileInsert]);
       }
        return $this->successResponse(
          $fileInsert,
          202
        );
      }else{
        return $this->errorResponse('Adjuntar archivo', 402);
      }
    } else {
      //return 'no tienes permiso';
      return $this->errorResponse('No tienes permiso', 402);
    }
  }
}