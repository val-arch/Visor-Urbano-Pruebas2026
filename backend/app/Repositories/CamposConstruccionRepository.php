<?php

namespace App\Repositories;

use App\DTOs\CamposRolesConstruccion\CampoCreateDto;
use App\DTOs\CamposRolesConstruccion\CampoUpdateDto;
use App\DTOs\CamposRolesConstruccion\CampoUpdateDto2;
use App\Models\CampoConstruccion;
use App\Models\RequisitoConstruccion;
use App\Models\ConsultaRequisitoConstruccion;
use App\Models\Refrendo;
use App\Repositories\Interfaces\ICampoConstruccionRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Models\ResolucionConstruccion;

class CamposConstruccionRepository implements ICampoConstruccionRepository
{
    public function paginate(int $take, int $municipio, string $filter)
    {
        /* 
            28/04/2021
           Se comento para mostrar solo los requisitos creados por el usuario
        */
        /* return   DB::table("campos_construccion")->orderby('campos_construccion.id','desc')
                ->select('campos_construccion.*',
                 DB::raw("(select id  from requisitos where campos_id=campos_construccion.id and municipios_id=$municipio) as existe"))
                ->whereIn('campos_construccion.id_municipio', [$municipio,0])
                ->where('campos_construccion.campo_estatico',0)
                ->paginate($take);
                */
        $valor = "";
        if ($filter != "") {
            $valor =  $filter;
        }

        if ($take == 0) {
            $take = 20;
        }

        if ($filter != '') {
            $db = DB::table("campos_construccion")->orderby('campos_construccion.id', 'desc')
                ->join('tipo_tramites_construccion', 'campos_construccion.tramite_relacionado', '=', 'tipo_tramites_construccion.id')
                ->select(
                    'campos_construccion.*',
                    DB::raw("(select id  from requisitos_construccion where campos_id=campos_construccion.id and municipios_id=$municipio) as id_requi, campos_construccion.id_municipio as existe"),
                    'tipo_tramites_construccion.tramite'
                )
                ->where('campos_construccion.campo_estatico', 0)
                ->where('campos_construccion.mostrar', true)
                ->where(function ($query) use ($municipio) {
                    $query->where('campos_construccion.id_municipio', 0)
                        ->where('campos_construccion.type', 'file')
                        ->Orwhere('campos_construccion.id_municipio', $municipio);
                })->Where(function ($query) use ($filter) {
                    $query->Orwhere('campos_construccion.description', 'like', '%' . $filter . '%')
                        ->Orwhere('campos_construccion.name', 'like', '%' . $filter . '%')
                        ->Orwhere('campos_construccion.type', 'like', '%' . $filter . '%');
                })
                ->paginate(10);
            // ->dump();
            return $db;
        } else {
            return  DB::table("campos_construccion")->orderby('campos_construccion.id', 'desc')
                ->leftjoin('tipo_tramites_construccion', 'campos_construccion.tramite_relacionado', '=', 'tipo_tramites_construccion.id')
                ->select(
                    'campos_construccion.*', 
                    DB::raw("(select id  from requisitos_construccion where campos_id=campos_construccion.id and municipios_id=$municipio) as id_requi, campos_construccion.id_municipio as existe"),
                     'tipo_tramites_construccion.tramite'
                )
                ->whereIn('campos_construccion.id_municipio', [$municipio, 0])
                ->where('campos_construccion.campo_estatico', 1)
                ->where(function ($query) {
                    $query->where('campos_construccion.id_municipio', 0)
                        ->where('campos_construccion.type', 'file');
                })->orWhere(function ($query) use ($municipio) {
                    $query->where('campos_construccion.id_municipio', $municipio)
                    ->where('campos_construccion.mostrar', true);
                })
                ->paginate(10);
        }
    }

    public function getCampos(int $municipio, $folio)
    {
        $folio2       =  base64_decode($folio);
        $folio        =  base64_decode($folio);
        $folio        = ConsultaRequisitoConstruccion::where('folio', $folio)->get();
        $tramite_rel       = $folio[0]->tramite_relacionado;
        $id_municipio = $folio[0]->id_municipio;
        $folio        = $folio[0]->id;

        $users = DB::table('campos_construccion')
            ->where('tramite_relacionado', null)
            ->orderby('secuencia', 'asc')
            ->orderby('campos_construccion.id', 'asc')
            ->join('requisitos_construccion', 'campos_construccion.id', '=', 'requisitos_construccion.campos_id')
            ->leftJoin('respuestas_construccion', 'campos_construccion.name', '=', 'respuestas_construccion.name')
            ->where('municipios_id', $id_municipio)
            ->where('respuestas_construccion.id_tramite', $folio)
            ->whereNull('campos_construccion.condicion_dependencia')
            ->select('campos_construccion.*', 'requisitos_construccion.*', 'respuestas_construccion.value')
            ->paginate(200);

        $anexo = DB::table('campos_construccion')
        ->where('tramite_relacionado', $tramite_rel)
        ->orderby('campos_construccion.id', 'asc')
        ->join('requisitos_construccion', 'campos_construccion.id', '=', 'requisitos_construccion.campos_id')
        ->leftJoin('respuestas_construccion', 'campos_construccion.name', '=', 'respuestas_construccion.name')
        ->where('municipios_id', $id_municipio)
        ->where('respuestas_construccion.id_tramite', $folio)
        ->whereNull('campos_construccion.condicion_dependencia')
         ->select('campos_construccion.*', 'requisitos_construccion.*', 'respuestas_construccion.*')
        ->paginate(200);


        /*$temp_anexo= array();


        foreach($anexo as $anexo_temp){

            if($anexo_temp->pregunta_si_o_no_value == 1){

                $nombre = DB::table('campos_construccion')
                ->where('campos_construccion.description', $anexo_temp->pregunta_si_o_no_condicion)
                ->select('campos_construccion.name')
                ->first();

                $usersDataRespuestas = DB::table('respuestas_construccion')
                ->where('respuestas_construccion.id_tramite', $folio)
                ->where('respuestas_construccion.name', $nombre->name)
                ->select('respuestas_construccion.value')
                ->first();

                $cond1 = false;

                $cond2 = false;

                if($usersDataRespuestas->value == "false"){
                    $cond1 = false;
                }else{
                    $cond1 = true;
                }

                if($anexo_temp->pregunta_si_o_no_value == 1){
                    $cond2 = true;
                }else{
                    $cond1 = false;
                }

                if($cond1== $cond2){
                    array_push($temp_anexo, $anexo_temp);
                    
                }
            }

        }*/


        
        


            

        $usersData = DB::table('campos_construccion')
            ->orderby('secuencia', 'asc')
            ->orderby('campos_construccion.id', 'asc')
            ->where('campos_construccion.campo_estatico', 1)
            ->select('campos_construccion.*')
            ->paginate(200);

        $usersData2 = DB::table('licencias_construccion_visor')
            ->where('folio', $folio2)
            ->select('*')
            ->paginate(100);

        $usersDataRespuestas = DB::table('respuestas_construccion')
            ->where('respuestas_construccion.id_tramite', $folio)
            ->where('respuestas_construccion.value', '!=', null)
            ->select('respuestas_construccion.name', 'respuestas_construccion.value')
            ->paginate(100);

        
        
        
        for ($i = 0; $i <= 3; $i++) {

            if (isset($usersDataRespuestas[$i]->name)) {
                error_log($usersDataRespuestas[$i]->name);

                if ($usersDataRespuestas[$i]->name == 'anexo_1') {
                    $usersData[0]->value = $usersDataRespuestas[$i]->value;
                }
                if ($usersDataRespuestas[$i]->name == 'anexo_2') {
                    $usersData[1]->value = $usersDataRespuestas[$i]->value;
                }
                if ($usersDataRespuestas[$i]->name == 'anexo_3') {
                    $usersData[2]->value = $usersDataRespuestas[$i]->value;
                }
                if ($usersDataRespuestas[$i]->name == 'anexo_4') {
                    $usersData[3]->value = $usersDataRespuestas[$i]->value;
                }
            }
        }

        $array[]  = $users;
        $array4[]  = $usersData2;
        $array2[] = $usersData;
        $array3[] = $array[0];
        //$anexo2[] = $temp_anexo; 
        array_push($array3, $array2);
        array_push($array3, $array4);
        //array_push($array3, $anexo2);

        return $array3;
    }


    public function getCampos2(int $municipio, $folio)
    {
        $folio2       =  base64_decode($folio);
        $folio        =  base64_decode($folio);
        $folio        = ConsultaRequisitoConstruccion::where('folio', $folio)->get();
        $tramite_rel       = $folio[0]->tramite_relacionado;
        $id_municipio = $folio[0]->id_municipio;
        $folio        = $folio[0]->id;

        $users = DB::table('campos_construccion')
            ->where('tramite_relacionado', null)
            ->orderby('secuencia', 'asc')
            ->orderby('campos_construccion.id', 'asc')
            ->join('requisitos_construccion', 'campos_construccion.id', '=', 'requisitos_construccion.campos_id')
            ->leftJoin('respuestas_construccion', 'campos_construccion.name', '=', 'respuestas_construccion.name')
            ->where('municipios_id', $id_municipio)
            ->where('respuestas_construccion.id_tramite', $folio)
            ->whereNull('campos_construccion.condicion_dependencia')
            ->select('campos_construccion.*', 'requisitos_construccion.*', 'respuestas_construccion.value')
            ->paginate(200);

        $anexo = DB::table('campos_construccion')
        ->whereIn('tramite_relacionado', [$tramite_rel, 0])
        ->orderby('campos_construccion.id', 'asc')
        ->join('requisitos_construccion', 'campos_construccion.id', '=', 'requisitos_construccion.campos_id')
        ->leftJoin('respuestas_construccion', 'campos_construccion.name', '=', 'respuestas_construccion.name')
        ->where('municipios_id', $id_municipio)
        ->where('respuestas_construccion.id_tramite', $folio)
        ->whereNull('campos_construccion.condicion_dependencia')
         ->select('campos_construccion.*', 'requisitos_construccion.*', 'respuestas_construccion.*')
        ->paginate(200);


        $temp_anexo= array();


        foreach($anexo as $anexo_temp){
            error_log($anexo_temp->name);
            switch ($anexo_temp->type) {
                case 'file':
                    if($anexo_temp->pregunta_si_o_no_value == 0){
                        array_push($temp_anexo, $anexo_temp);
                    }else if($anexo_temp->pregunta_si_o_no_value == 1){
                        $nombre = DB::table('campos_construccion')
                        ->where('campos_construccion.description', $anexo_temp->pregunta_si_o_no_condicion)
                        ->select('campos_construccion.name')
                        ->first();

                        $usersDataRespuestas = DB::table('respuestas_construccion')
                        ->where('respuestas_construccion.id_tramite', $folio)
                        ->where('respuestas_construccion.name', $nombre->name)
                        ->select('respuestas_construccion.value')
                        ->first();

                        $cond1 = false;
                        $cond2 = false;

                        if($usersDataRespuestas->value == "false"){
                            $cond1 = false;
                        }else{
                            $cond1 = true;
                        }

                        if($anexo_temp->pregunta_si_o_no_value == 1){
                            $cond2 = true;
                        }else{
                            $cond2 = false;
                        }

                        if($cond1== $cond2){
                            array_push($temp_anexo, $anexo_temp);
                        }
                    }
                    break;
                case 'input':
                    array_push($temp_anexo, $anexo_temp);
                    break;
            }
        }

        $usersData = DB::table('campos_construccion')
            ->orderby('secuencia', 'asc')
            ->orderby('campos_construccion.id', 'asc')
            ->where('campos_construccion.campo_estatico', 1)
            ->select('campos_construccion.*')
            ->paginate(200);

        $usersData2 = DB::table('licencias_construccion_visor')
            ->where('folio', $folio2)
            ->select('*')
            ->paginate(100);

        $usersDataRespuestas = DB::table('respuestas_construccion')
            ->where('respuestas_construccion.id_tramite', $folio)
            ->where('respuestas_construccion.value', '!=', null)
            ->select('respuestas_construccion.name', 'respuestas_construccion.value')
            ->paginate(100);

        
        
        
        

        $array[]  = $users;
        $array4[]  = $usersData2;
        $array2[] = $usersData;
        $array3[] = $array[0];
        $anexo2[] = $temp_anexo; 
        array_push($array3, $array2);
        array_push($array3, $array4);
        array_push($array3, $anexo2);

        return $array3;
    }
    public function getCamposId(int $municipio, $folio, $id)
    {
        $folio =  base64_decode($folio);
        $folio = ConsultaRequisitoConstruccion::where('id', $id)->get();
        $id_municipio = $folio[0]->id_municipio;
        $folio = $folio[0]->id;

        $users = DB::table('campos_construccion')
            ->orderby('secuencia', 'asc')
            ->orderby('campos_construccion.id', 'asc')
            ->join('requisitos_construccion', 'campos_construccion.id', '=', 'requisitos_construccion.campos_id')
            ->leftJoin('respuestas_construccion', 'campos_construccion.name', '=', 'respuestas_construccion.name')
            ->where('municipios_id', $id_municipio)
            ->where('respuestas_construccion.id_tramite', $folio)
            ->whereNull('campos_construccion.condicion_dependencia')
            ->select('campos_construccion.*', 'requisitos.*', 'respuestas_construccion.value')
            ->paginate(100);

        $usersData = DB::table('campos_construccion')
            ->orderby('secuencia', 'asc')
            ->orderby('campos_construccion.id', 'asc')
            ->where('campos_construccion.campo_estatico', 1)
            ->select('campos_construccion.*')
            ->paginate(100);

        $usersDataRespuestas = DB::table('respuestas_construccion')
            ->where('respuestas_construccion.id_tramite', $folio)
            ->whereIn('respuestas_construccion.name', ['anexo_1', 'anexo_3', 'anexo_4', 'anexo_2'])
            ->select('respuestas_construccion.name', 'respuestas_construccion.value')
            ->paginate(100);



        for ($i = 0; $i <= 3; $i++) {

            if (isset($usersDataRespuestas[$i]->name)) {

                if ($usersDataRespuestas[$i]->name == 'anexo_1') {
                    $usersData[0]->value = $usersDataRespuestas[$i]->value;
                }
                if ($usersDataRespuestas[$i]->name == 'anexo_2') {
                    $usersData[1]->value = $usersDataRespuestas[$i]->value;
                }
                if ($usersDataRespuestas[$i]->name == 'anexo_3') {
                    $usersData[2]->value = $usersDataRespuestas[$i]->value;
                }
                if ($usersDataRespuestas[$i]->name == 'anexo_4') {

                    $usersData[3]->value = $usersDataRespuestas[$i]->value;
                }
            }
        }

        $array[]  = $users;
        $array2[] = $usersData;
        $array3[] = $array[0];
        array_push($array3, $array2);
        return $array3;
    }
    public function find(int $id): ?CampoConstruccion
    {
        return CampoConstruccion::find($id);
    }
    function base64_decode_if_needed($str)
    {
        $decoded = base64_decode($str, true);
        if ($str === base64_encode($decoded)) {
            return $decoded;
        }
        return $str;
    }


    public function store(CampoCreateDto $store): CampoConstruccion
    {
        $campos2 = $this->base64_decode_if_needed($store->condicion_visible);
        $entry                           = new CampoConstruccion();
        $entry->name                     = $store->name;
        $entry->type                     = $store->type;
        $entry->description              = $store->description;
        $entry->description_rec          = $store->description_rec;
        $entry->fundamento               = $store->fundamento;
        $entry->opciones                 = $store->opciones == '' ? null : $store->opciones;
        $entry->opciones_desc            = $store->opciones_desc == '' ? null : $store->opciones_desc;
        $entry->secuencia                = $store->secuencia;
        $entry->requerido                = $store->requerido;
        $entry->condicion_visible        = $campos2;
        $entry->condicion_dependencia    = $store->condicion_dependencia == '' ? null : $store->condicion_dependencia;
        $entry->condicion_giro           = $store->condicion_giro == '' ? null : $store->condicion_giro;
        $entry->campo_afectado           = $store->campo_afectado == '' ? null : $store->campo_afectado;
        $entry->tipo_tramite             = $store->tipo_tramite;
        $entry->status                   = $store->status;
        $entry->step                     = $store->step;
        $entry->tramite_relacionado      = $store->tramite_relacionado;
        $entry->id_municipio             = $store->id_municipio;
        $entry->actividad_condicion = $store->actividad_condicion;
        $entry->actividad_metros = $store->actividad_metros;
        $entry->construccion_total_metros_condicion = $store->construccion_total_metros_condicion;
        $entry->construccion_total_metros_value = $store->construccion_total_metros_value;
        $entry->construccion_uso = $store->construccion_uso;
        $entry->construccion_uso_destino = $store->construccion_uso_destino;
        $entry->construccion_uso_metros = $store->construccion_uso_metros;
        $entry->demolicion_metros_condicion = $store->demolicion_metros_condicion;
        $entry->demolicion_metros_value = $store->demolicion_metros_value;
        $entry->nivel_nuevo_construccion_condicion = $store->nivel_nuevo_construccion_condicion;
        $entry->nivel_nuevo_construccion_value = $store->nivel_nuevo_construccion_value;
        $entry->sotano_nuevo_construccion_condicion = $store->sotano_nuevo_construccion_condicion;
        $entry->sotano_nuevo_construccion_value = $store->sotano_nuevo_construccion_value;
        $entry->todasCondiciones = $store->todasCondiciones;
        $entry->viviendas_construccion_condicion = $store->viviendas_construccion_condicion;
        $entry->viviendas_construccion_value = $store->viviendas_construccion_value;
        $entry->pregunta_si_o_no_condicion = $store->pregunta_si_o_no_condicion;
        $entry->pregunta_si_o_no_value = $store->pregunta_si_o_no_value;
        $entry->info_licencia    = $store->info_licencia;
        $entry->save();

        $entry_requisitos                 = new RequisitoConstruccion();
        $entry_requisitos->municipios_id  = $entry->id_municipio;
        $entry_requisitos->campos_id      = $entry->id;
        $entry_requisitos->id_requisitos  = "null";
        $entry_requisitos->save();

        if($store->condicion_dependencia != ''){
            $resolucionExistente = ResolucionConstruccion::where('id_municipio', $store->id_municipio)->where('id_rol', $store->condicion_dependencia)->where('tipo_tramite', $store->tramite_relacionado)->first();

            if(!$resolucionExistente){
                $entry_res                 = new ResolucionConstruccion();
                $entry_res->id_campo = $entry->id;
                $entry_res->id_municipio = $store->id_municipio;
                $entry_res->id_rol = $store->condicion_dependencia;
                $entry_res->orden = null;
                $entry_res->tipo_tramite = $store->tramite_relacionado;
                $entry_res->mostrar = true;
                $entry_res->save();
            }

        }
        return $entry;
    }

    public function update(CampoUpdateDto $store): void
    {
        $entry                   = CampoConstruccion::find($store->id);

        $entry->type             = $store->type;
        $entry->description      = $store->description;
        $entry->description_rec  = $store->description_rec;
        $entry->fundamento       = $store->fundamento;
        $entry->opciones          = $store->opciones == '' ? null : $store->opciones;
        $entry->opciones_desc     = $store->opciones_desc == '' ? null : $store->opciones_desc;
        $entry->secuencia        = $store->secuencia;
        $entry->requerido        = $store->requerido;
        $entry->condicion_visible = base64_decode($store->condicion_visible);
        $entry->condicion_giro    = $store->condicion_giro == '' ? null : $store->condicion_giro;
        $entry->condicion_dependencia    = $store->condicion_dependencia == '' ? null : $store->condicion_dependencia;
        $entry->campo_afectado   = $store->campo_afectado;
        $entry->tipo_tramite     = $store->tipo_tramite;
        $entry->step             = $store->step;
        $entry->tramite_relacionado            = $store->tramite_relacionado;
        $entry->info_licencia    = $store->info_licencia;

        $entry->actividad_condicion = $store->actividad_condicion;
        $entry->actividad_metros = $store->actividad_metros;
        $entry->construccion_total_metros_condicion = $store->construccion_total_metros_condicion;
        $entry->construccion_total_metros_value = $store->construccion_total_metros_value;
        $entry->construccion_uso = $store->construccion_uso;
        $entry->construccion_uso_destino = $store->construccion_uso_destino;
        $entry->construccion_uso_metros = $store->construccion_uso_metros;
        $entry->demolicion_metros_condicion = $store->demolicion_metros_condicion;
        $entry->demolicion_metros_value = $store->demolicion_metros_value;
        $entry->nivel_nuevo_construccion_condicion = $store->nivel_nuevo_construccion_condicion;
        $entry->nivel_nuevo_construccion_value = $store->nivel_nuevo_construccion_value;
        $entry->sotano_nuevo_construccion_condicion = $store->sotano_nuevo_construccion_condicion;
        $entry->sotano_nuevo_construccion_value = $store->sotano_nuevo_construccion_value;
        $entry->todasCondiciones = $store->todasCondiciones;
        $entry->viviendas_construccion_condicion = $store->viviendas_construccion_condicion;
        $entry->viviendas_construccion_value = $store->viviendas_construccion_value;
        $entry->pregunta_si_o_no_condicion = $store->pregunta_si_o_no_condicion;
        $entry->pregunta_si_o_no_value = $store->pregunta_si_o_no_value;

        $entry->save();

        if($store->condicion_dependencia != '' ){
            $update = ResolucionConstruccion::where('id_campo', $entry->id)->update(
                ['id_rol'=> $store->condicion_dependencia, 'tipo_tramite' => $store->tramite_relacionado]
            );
            if($update == 0){
                $resolucionExistente = ResolucionConstruccion::where('id_municipio', $store->id_municipio)->where('id_rol', $store->condicion_dependencia)->where('tipo_tramite', $store->tramite_relacionado)->first();
                if(!$resolucionExistente){
                    if($store->condicion_dependencia != '' ){
                        $entry_res                 = new ResolucionConstruccion();
                        $entry_res->id_campo = $entry->id;
                        $entry_res->id_municipio = $store->id_municipio;
                        $entry_res->id_rol = $store->condicion_dependencia;
                        $entry_res->orden = null;
                        $entry_res->tipo_tramite = $store->tramite_relacionado;
                        $entry_res->mostrar = true;
                        $entry_res->save();
                    }
                }
            }  
        }else{
            ResolucionConstruccion::where('id_campo', $entry->id)->delete();
        }

        


    }
    public function update2(CampoUpdateDto2 $store): void
    {
        $entry                   = CampoConstruccion::find($store->id);

        $entry->mostrar             = $store->mostrar;

        $entry->save();
    }

    public function camposRequisitos($tipo_tramite)
    {
        return CampoConstruccion::query()
            ->where('tipo_tramite', 'LIKE', "%{$tipo_tramite}%")
            ->get();
    }

    public function camposRequisitosFile($id_municipio)
    {
        return  DB::table("campos_construccion")
            ->join('requisitos_construccion', 'campos_construccion.id', '=', 'requisitos_construccion.campos_id')
            ->select('*')
            ->whereIn('municipios_id', [$id_municipio, 0])
            ->where('requisitos_construccion.municipios_id', $id_municipio)
            ->get();
    }

    public function camposRequisitoTramite($id_municipio)
    {
        //return $id_municipio;
        return  DB::table("campos_construccion")
            ->join('requisitos_construccion', 'campos_construccion.id', '=', 'requisitos_construccion.campos_id')
            ->select('campos_construccion.*')
            //->whereIn('campos_construccion.id_municipio',[$id_municipio,0])
            ->whereIn('requisitos_construccion.municipios_id', [$id_municipio])
            ->whereIn('campos_construccion.tipo_tramite', ['consulta', 'consulta_requisitos'])
            ->orderBy('id', 'asc')
            ->get();
    }

    public function destroy(int $id): void
    {
        CampoConstruccion::destroy($id);
    }
    public function agregarRequisito($id_municipio, $campo_id, $idRequisito): void
    {
        if ($idRequisito == 0) {
            $entry_requisitos                 = new RequisitoConstruccion();
            $entry_requisitos->municipios_id  = $id_municipio;
            $entry_requisitos->campos_id      = $campo_id;
            $entry_requisitos->id_requisitos  = "null";
            $entry_requisitos->save();
            CampoConstruccion::where('id', $campo_id)->update(['active' => true]);
        } else {
            RequisitoConstruccion::destroy($idRequisito);
            CampoConstruccion::where('id', $campo_id)->update(['active' => false]);
        }
    }
}
