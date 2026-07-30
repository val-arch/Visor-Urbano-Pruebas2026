<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Models\ResolucionConstruccion;
use Illuminate\Support\Facades\DB;



class ResolucionConstruccionController extends Controller
{
    //
    use ApiResponser;
    public function getOrdenResolucion($id_municipio, $tipo_tramite)
    {
        $orden = ResolucionConstruccion::where('id_municipio', $id_municipio)->where('tipo_tramite', $tipo_tramite)->where('mostrar', true)->orderBy('orden', 'asc')->get();
        $array = array();
        foreach($orden as $value){
            $data = array();
            $role = DB::table('roles_construccion')->where('id', $value->id_rol)->get();

            $data =  array(
                'id' => $value->id,
                'role' => $role[0]->name,
                'orden' => $value->orden,
                'tipo_tramite' => $tipo_tramite,
            );
            array_push($array, $data);
        }
        return $array;
    }

    public function updateOrdenResolucion($id, $value, $tipo_tramite)
    {
        $data = ResolucionConstruccion::where('id', $id)->first();

        /*$orden = $this->getOrdenResolucion($data->id_municipio, $tipo_tramite);

        $keys = array_column($orden, 'orden');

        $index_ini = array_search($value, $keys);

        $index_final = array_search($data->orden, $keys);

        for($x = $index_ini; $x<$index_final; $x++){
            $this->updateOrdenResolucion2($data->id_municipio, $keys[$x], $keys[$x]+1);
        }*/

        $data->orden = $value;

        if($data->save()){
            return $this->successResponse(202);
        }else{
            return $this->errorResponse('Product not found', 404);  
        }
    }

    public function updateOrdenResolucion2($id_municipio, $value, $new_value)
    {
        $data = ResolucionConstruccion::where('id_municipio', $id_municipio)->where('orden', $value)->first();
        $data->orden = $new_value;
        if($data->save()){
            return $this->successResponse(202);
        }else{
            return $this->errorResponse('Product not found', 404);  
        }

    }
}
