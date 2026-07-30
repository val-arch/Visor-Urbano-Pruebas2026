<?php

namespace App\Http\Controllers;
use App\Models\HistoricoLicencia;
use App\Models\LicenciaGiroVisor;
use App\Models\Refrendo;
use App\Traits\ApiResponser;
use App\Traits\LogHistoricoConstruccion;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\TramiteConstruccion;
use App\Repositories\Interfaces\ILogs;

class TextConstruccionController extends Controller
{
  use ApiResponser;
  use LogHistoricoConstruccion;


  public function guardarTextId(Request $request)
  {
    $folio            = base64_decode($request->all()['folio']);
    $step_actual      = $request->all()['step_actual'];
    $usuarioActual_id = Auth::user()->id;
    $datos            = $request->all();
    foreach ($datos as $clave => $value) {
      if ($value == "") {
        unset($datos[$clave]);
      }
    }

    $finals = collect($datos)->values();
    $names1 = collect($datos)->keys();
    // dd($finals,$names1,$test );

    $query = User::select('user_roles_construccion.id', 'user_roles_construccion.role_id')
      ->join('user_roles_construccion', 'users.id', '=', 'user_roles_construccion.user_id')
      ->where('user_id', '=',  $usuarioActual_id)
      ->get();
    $st = isset($query[0]) ? $query[0] : false;
    if (!$st) {
      return $this->errorResponse('Este usuario no tiene asignado un role', 404);
    }
    $role = $query[0]->role_id;
    $query2 =  DB::table('consulta_requisitos_construccion')
      ->select('*')
      ->where('folio', $folio)
      ->get();
    $usuarioFolio = $query2[0]->id_usuario;
    $idConsulta = $query2[0]->id;

    $data2 = collect($datos);
    $valor_anterior = $step_actual;
    $this->historialConstruccion($accion = 'Se registro un nuevo step', $valor_anterior, $data2, $tipo = 2, 0);

    if ($role > 1 || isset($usuarioFolio) && ($role == 1 && $usuarioActual_id == $usuarioFolio)) {
      $test = collect($datos);
      foreach ($test as $key => $value) {
        if ($value == 'null') {
          $value = '';
        }
        if ($key != 'folio' || $key != 'step_actual') {
          $respuesta =  DB::table('respuestas_construccion')
            ->select('*')
            ->where('name', '=', $key)
            ->where('id_tramite', '=', $idConsulta)
            ->first();
          $data =  array(
            'id_tramite' => $idConsulta,
            'name' => $key,
            'value' => $value,
            'id_usuario' => $usuarioActual_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
          );
          $valor = array(
            $key => $value,
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
            DB::table('respuestas_construccion_json')->insert($data2);
          } else {
            DB::table('respuestas_construccion')->where('name', '=', $key)
              ->where('id_tramite', '=', $idConsulta)->update($data);

            DB::table('respuestas_construccion_json')
              ->where('respuestas->name', '=', $key)
              ->where('id_tramite', '=', $idConsulta)->update($data2);
          }
        }
      }


      if ($step_actual == 1) {

        $tramite = TramiteConstruccion::where('folio',  $folio)->first();
        $tramite->step_uno =  1;
        $tramite->step_actual =  $step_actual;
        $tramite->save();
      }
      if ($step_actual == 2) {
        $tramite = TramiteConstruccion::where('folio',  $folio)->first();
        $tramite->step_dos =  1;
        $tramite->step_actual =  $step_actual;
        $tramite->save();
      }
      if ($step_actual == 3) {
        $tramite = TramiteConstruccion::where('folio',  $folio)->first();
        $tramite->step_tres =  1;
        $tramite->step_actual =  $step_actual;
        $tramite->save();
      }
      if ($step_actual == 4) {
        $tramite = TramiteConstruccion::where('folio',  $folio)->first();
        $tramite->step_cuatro =  1;
        $tramite->step_actual =  $step_actual;
        $tramite->save();
      }

      return $this->successResponse(
        "success",
        202
      );
    } else {
      return 'no tienes permiso';
    }
  }

  public function guardarText(Request $request)
  {
    $folio            = base64_decode($request->all()['folio']);
    $step_actual      = $request->all()['step_actual'];
    $usuarioActual_id = Auth::user()->id;
    $datos            = $request->all();
    if ($step_actual == 4 && isset($datos['hora_a'])) {

      $hora_a     = $datos['hora_a'];
      $hora_c     = $datos['hora_c'];
      $superficie = $datos['superficie'];
      unset($datos['hora_a']);
      unset($datos['hora_c']);
      unset($datos['superficie']);
    }
    foreach ($datos as $clave => $value) {
      if ($value == "") {
        unset($datos[$clave]);
      }
    }
    $finals = collect($datos)->values();
    $names1 = collect($datos)->keys();
    // dd($finals,$names1,$test );

    $query = User::select('user_roles_construccion.id', 'user_roles_construccion.role_id')
      ->join('user_roles_construccion', 'users.id', '=', 'user_roles_construccion.user_id')
      ->where('user_id', '=',  $usuarioActual_id)
      ->get();
    $st = isset($query[0]) ? $query[0] : false;
    if (!$st) {
      return $this->errorResponse('Este usuario no tiene asignado un role', 404);
    }
    $role = $query[0]->role_id;
    $query2 =  DB::table('consulta_requisitos_construccion')
      ->select('*')
      ->where('folio', $folio)
      ->get();
    $usuarioFolio = $query2[0]->id_usuario;
    $idConsulta = $query2[0]->id;

    $data2 = collect($datos);
    $valor_anterior = $step_actual;
    $this->historialConstruccion($accion = 'Se registro un nuevo step', $valor_anterior, $data2, $tipo = 2, 0);
    if ($role > 1 || isset($usuarioFolio) && ($role == 1 && $usuarioActual_id == $usuarioFolio)) {
      $test = collect($datos);
      foreach ($test as $key => $value) {
        if ($value == 'null') {
          $value = '';
        }
        if ($key != 'folio' || $key != 'step_actual') {
          $respuesta =  DB::table('respuestas_construccion')
            ->select('*')
            ->where('name', '=', $key)
            ->where('id_tramite', '=', $idConsulta)
            ->first();
          $data =  array(
            'id_tramite' => $idConsulta,
            'name' => $key,
            'value' => $value,
            'id_usuario' => $usuarioActual_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
          );
          $valor = array(
            $key => $value,
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
            DB::table('respuestas_construccion_json')->insert($data2);
          } else {
            DB::table('respuestas_construccion')->where('name', '=', $key)
              ->where('id_tramite', '=', $idConsulta)->update($data);

            DB::table('respuestas_construccion_json')
              ->where('respuestas->name', '=', $key)
              ->where('id_tramite', '=', $idConsulta)->update($data2);
          }
        }
      }


      if ($step_actual == 1) {

        $tramite = TramiteConstruccion::where('folio',  $folio)->first();
        $tramite->step_uno =  1;
        $tramite->step_actual =  $step_actual;
        $tramite->save();
      }
      if ($step_actual == 2) {
        $tramite = TramiteConstruccion::where('folio',  $folio)->first();
        $tramite->step_dos =  1;
        $tramite->step_actual =  $step_actual;
        $tramite->save();
      }
      if ($step_actual == 3) {
        $tramite = TramiteConstruccion::where('folio',  $folio)->first();
        $tramite->step_tres =  1;
        $tramite->step_actual =  $step_actual;
        $tramite->save();
      }
      if ($step_actual == 4) {
        $tramite = TramiteConstruccion::where('folio',  $folio)->first();
        $tramite->step_cuatro =  1;
        $tramite->step_actual =  $step_actual;
        $tramite->save();
      }
      if ($step_actual == 5) {
        $tramite = TramiteConstruccion::where('folio',  $folio)->first();
        $tramite->step_cinco =  1;
        $tramite->step_actual =  $step_actual;
        $tramite->save();
        if ($step_actual == 5 && isset($datos['hora_a'])) {
          $licencia = LicenciaGiroVisor::where('folio',  $folio)->first();
          $licencia->hora_a =  $hora_a;
          $licencia->hora_c =  $hora_c;
          $licencia->superficie_autorizada =  $superficie;
          $licencia->save();
        }
      }

      return $this->successResponse(
        "success",
        202
      );
    } else {
      return 'no tienes permiso';
    }
  }

  public function guardarTextRefrendo(Request $request)
  {
    
  }

  public function guardarHistoricoRefrendo(Request $request)
  {
    $datos            = $request->all();
    if ($datos['step_actual'] == 1) {
      if ($datos['id'] == "null") {
        $historico = new  HistoricoLicencia;
      } else {
        $historico = HistoricoLicencia::where('id',    $datos['id'])->first();
      }
      $historico->razon_social       = $datos['razon_social']   == 'null' ? '' : $datos['razon_social'];
      $historico->nombre_titular     = $datos['nombre']         == 'null' ? '' : $datos['nombre'];
      $historico->apellido_p         = $datos['apellido_p']     == 'null' ? '' : $datos['apellido_p'];
      $historico->apellido_m         = $datos['apellido_m']     == 'null' ? '' : $datos['apellido_m'];
      $historico->calle              = $datos['domicilio']      == 'null' ? '' : $datos['domicilio'];
      $historico->curp               = $datos['curp']           == 'null' ? '' : $datos['curp'];
      $historico->rfc                = $datos['rfc']            == 'null' ? '' : $datos['rfc'];
      $historico->email              = $datos['correo']         == 'null' ? '' : $datos['correo'];
      $historico->telefono           = $datos['telefono']       == 'null' ? '' : $datos['telefono'];
      $historico->cp_titular         = $datos['cp']             == 'null' ? '' :  $datos['cp'];
      $historico->step_1             = $datos['step_actual']    == 'null' ? '' : $datos['step_actual'];
      $historico->colonia_titular    = $datos['colonia']        == 'null' ? '' : $datos['colonia'];
      $historico->numero_ext_titular = $datos['num_ext']        == 'null' ? '' : $datos['num_ext'];
      $historico->numero_int_titular = $datos['num_int']        == 'null' ? '' : $datos['num_int'];
      $historico->folio_licencia     = $datos['folio_actual']   == 'null' ? '' : $datos['folio_actual'];
      $historico->id_municipio       = $datos['id_municipio']   == 'null' ? '' : $datos['id_municipio'];
      $historico->save();
      $productId = $historico->id;
    }
    if ($datos['step_actual'] == 2) {
      if ($datos['id'] == "null") {
        $historico = new  HistoricoLicencia;
      } else {
        $historico = HistoricoLicencia::where('id', $datos['id'])->first();
      }
      $historico->nombre_solicitante       = $datos['nombre_propietario']     == 'null' ? '' : $datos['nombre_propietario'];
      $historico->apellido_solicitante_p   = $datos['apellido_m_propietario'] == 'null' ? '' : $datos['apellido_m_propietario'];
      $historico->apellido_solicitante_m   = $datos['apellido_p_propietario'] == 'null' ? '' : $datos['apellido_p_propietario'];
      $historico->calle_solicitante        = $datos['domicilio_propietario']  == 'null' ? '' : $datos['domicilio_propietario'];
      $historico->curp_solicitante         = $datos['curp_propietario']       == 'null' ? '' : $datos['curp_propietario'];
      $historico->rfc_solicitante          = $datos['rfc_propietario']        == 'null' ? '' : $datos['rfc_propietario'];
      $historico->email_solicitante        = $datos['correo_propietario']     == 'null' ? '' : $datos['correo_propietario'];
      $historico->telefono_solicitante     = $datos['telefono_propietario']   == 'null' ? '' : $datos['telefono_propietario'];
      $historico->cp_solicitante           = $datos['cp_propietario']         == 'null' ? '' : $datos['cp_propietario'];
      $historico->step_2                   = 1;
      $historico->colonia                  = $datos['colonia_propietario']    == 'null' ? '' : $datos['colonia_propietario'];
      $historico->numero_int               = $datos['num_ext_propietario']    == 'null' ? '' : $datos['num_ext_propietario'];
      $historico->numero_ext               = $datos['num_int_propietario']    == 'null' ? '' : $datos['num_int_propietario'];
      $historico->save();
      $productId = $historico->id;
    }
    if ($datos['step_actual'] == 3) {
      if ($datos['id'] == "null") {
        $historico = new  HistoricoLicencia;
      } else {
        $historico = HistoricoLicencia::where('id', $datos['id'])->first();
      }
      $historico->calle_predio    = $datos['calle_predio']    == 'null' ? '' : $datos['calle_predio'];
      $historico->colonia_predio  = $datos['colonia_predio']  == 'null' ? '' : $datos['colonia_predio'];
      $historico->num_int_predio  = $datos['num_int_predio']  == 'null' ? '' :  $datos['num_int_predio'];
      $historico->num_ext_predio  = $datos['num_ext_predio']  == 'null' ? '' : $datos['num_ext_predio'];
      $historico->cp_predio       = $datos['cp_predio']       == 'null' ? '' : $datos['cp_predio'];
      $historico->coordonadas_x   = $datos['coordenadas_x']   == 'null' ? '' : $datos['coordenadas_x'];
      $historico->coordonadas_y   = $datos['coordenadas_y']   == 'null' ? '' : $datos['coordenadas_y'];
      $historico->clave_catastral = $datos['clave_cas']       == 'null' ? '' : $datos['clave_cas'];
      $historico->step_3 = 1;
      $historico->save();
      $productId = $historico->id;
    }
    if ($datos['step_actual'] == 4) {
      if ($datos['id'] == "null") {
        $historico = new  HistoricoLicencia;
      } else {
        $historico = HistoricoLicencia::where('id', $datos['id'])->firstOrFail();
      }
      $historico->tipo_inmueble   = $datos['tipo_inmueble'] == 'null' ? '' : $datos['tipo_inmueble'];
      $historico->nombre_negocio  = $datos['nombre_negocio']  == 'null' ? '' : $datos['nombre_negocio'];
      $historico->inversion       = $datos['inversion']  == 'null' ? '' : $datos['inversion'];
      $historico->numero_empleado = $datos['numero_empleado'] == 'null' ? '' : $datos['numero_empleado'];
      $historico->numero_cajones  = $datos['numero_cajones']  == 'null' ? '' : $datos['numero_cajones'];
      $historico->codigo_giro     = $datos['giro_codigo']  == 'null' ? '' : $datos['giro_codigo'];
      $historico->descripcion_detallada = $datos['giro_desc']  == 'null' ? '' : $datos['giro_desc'];
      $historico->superficie_giro = $datos['superficie']  == 'null' ? '' : $datos['superficie'];
      $historico->hora_a = $datos['hora_a']  == 'null' ? '' : $datos['hora_a'];
      $historico->hora_c = $datos['hora_c']  == 'null' ? '' : $datos['hora_c'];
      $historico->giro = $datos['giro']  == 'null' ? '' : $datos['giro'];
      $historico->step_4 = 1;
      $historico->save();
      $productId = $historico->id;
    }
    return $productId;
  }
}
