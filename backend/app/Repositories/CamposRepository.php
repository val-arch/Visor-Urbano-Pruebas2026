<?php

namespace App\Repositories;

use App\DTOs\CamposRoles\CampoCreateDto;
use App\DTOs\CamposRoles\CampoUpdateDto;
use App\Models\Campo;
use App\Models\Requisito;
use App\Models\ConsultaRequisito;
use App\Models\Refrendo;
use App\Repositories\Interfaces\ICampoRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CamposRepository implements ICampoRepository
{
    public function paginate(int $take, int $municipio, string $filter)
    {
        /* 
            28/04/2021
           Se comento para mostrar solo los requisitos creados por el usuario
        */
        /* return   DB::table("campos")->orderby('campos.id','desc')
                ->select('campos.*',
                 DB::raw("(select id  from requisitos where campos_id=campos.id and municipios_id=$municipio) as existe"))
                ->whereIn('campos.id_municipio', [$municipio,0])
                ->where('campos.campo_estatico',0)
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
            $db = DB::table("campos")->orderby('campos.id', 'desc')
                ->select(
                    'campos.*',
                    DB::raw("(select id  from requisitos where campos_id=campos.id and municipios_id=$municipio) as id_requi, campos.id_municipio as existe")
                )
                ->where('campos.campo_estatico', 0)
                ->where(function ($query) use ($municipio) {
                    $query->where('campos.id_municipio', 0)
                        ->where('campos.type', 'file')
                        ->Orwhere('campos.id_municipio', $municipio);
                })->Where(function ($query) use ($filter) {
                    $query->Orwhere('campos.description', 'ilike', '%' . $filter . '%')
                        ->Orwhere('campos.name', 'ilike', '%' . $filter . '%')
                        ->orWhereRaw('upper(unaccent("campos"."description")) ilike \'%'.strtoupper($filter).'%\'')
                        ->Orwhere('campos.type', 'ilike', '%' . $filter . '%');
                })
                ->paginate(10);
            // ->dump();
            return $db;
        } else {
            return  DB::table("campos")->orderby('campos.id', 'desc')
                ->select(
                    'campos.*',
                    DB::raw("(select id  from requisitos where campos_id=campos.id and municipios_id=$municipio) as id_requi, campos.id_municipio as existe")
                )
                ->whereIn('campos.id_municipio', [$municipio, 0])
                ->where('campos.campo_estatico', 0)
                ->where(function ($query) {
                    $query->where('campos.id_municipio', 0)
                        ->where('campos.type', 'file');
                })->orWhere(function ($query) use ($municipio) {
                    $query->where('campos.id_municipio', $municipio);
                })
                ->paginate(10);
        }
    }
    public function getCamposRefrendos(int $municipio, $folio)
    {

        $folio =  base64_decode($folio);
        $RefrendoTramite =  Refrendo::where('id', $folio)->firstOrFail();
        $folio = ConsultaRequisito::where('id', $RefrendoTramite->id_consulta_requisitos)->get();
        $id_municipio = $folio[0]->id_municipio;
        $folio = $folio[0]->id;

        $users = DB::table('campos')
            ->orderby('secuencia', 'asc')
            ->orderby('campos.id', 'asc')
            ->join('requisitos', 'campos.id', '=', 'requisitos.campos_id')
            ->leftJoin('respuestas', 'campos.name', '=', 'respuestas.name')
            ->where('municipios_id', $id_municipio)
            ->where('respuestas.id_tramite', $folio)
            ->whereNull('campos.condicion_dependencia')
            ->select('campos.*', 'requisitos.*', 'respuestas.value')
            ->paginate(100);

        $usersData = DB::table('campos')
            ->orderby('secuencia', 'asc')
            ->orderby('campos.id', 'asc')
            ->where('campos.campo_estatico', 1)
            ->select('campos.*')
            ->paginate(100);

        $usersDataRespuestas = DB::table('respuestas')
            ->where('respuestas.id_tramite', $folio)
            ->whereIn('respuestas.name', ['anexo_1', 'anexo_3', 'anexo_4', 'anexo_2'])
            ->select('respuestas.name', 'respuestas.value')
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

    public function getCampos(int $municipio, $folio)
    {   $folio2 =  base64_decode($folio);
        $folio =  base64_decode($folio);
     
        $folio = ConsultaRequisito::where('folio', $folio)->get();
        $id_municipio = $folio[0]->id_municipio;
        $folio = $folio[0]->id;

        $users = DB::table('campos')
            ->orderby('secuencia', 'asc')
            ->orderby('campos.id', 'asc')
            ->join('requisitos', 'campos.id', '=', 'requisitos.campos_id')
            ->leftJoin('respuestas', 'campos.name', '=', 'respuestas.name')
            ->where('municipios_id', $id_municipio)
            ->where('respuestas.id_tramite', $folio)
            ->whereNull('campos.condicion_dependencia')
            ->select('campos.*', 'requisitos.*', 'respuestas.value')
            ->paginate(100);

        $usersData = DB::table('campos')
            ->orderby('secuencia', 'asc')
            ->orderby('campos.id', 'asc')
            ->where('campos.campo_estatico', 1)
            ->select('campos.*')
            ->paginate(100);

        $usersData2 = DB::table('licencias_giro_visor')
            ->where('folio', $folio2)
            ->where('tipo_licencia', 'Nueva')
            ->select('hora_a','hora_c','superficie_autorizada')
            ->paginate(100);

        $usersDataRespuestas = DB::table('respuestas')
            ->where('respuestas.id_tramite', $folio)
            ->whereIn('respuestas.name', ['anexo_1', 'anexo_3', 'anexo_4', 'anexo_2'])
            ->select('respuestas.name', 'respuestas.value')
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
        $array4[]  = $usersData2;
        $array2[] = $usersData;
        $array3[] = $array[0];
        array_push($array3, $array2);
        array_push($array3, $array4);
        return $array3;
    }
    public function getCamposId(int $municipio, $folio, $id)
    {
        $folio =  base64_decode($folio);
        $folio = ConsultaRequisito::where('id', $id)->get();
        $id_municipio = $folio[0]->id_municipio;
        $folio = $folio[0]->id;

        $users = DB::table('campos')
            ->orderby('secuencia', 'asc')
            ->orderby('campos.id', 'asc')
            ->join('requisitos', 'campos.id', '=', 'requisitos.campos_id')
            ->leftJoin('respuestas', 'campos.name', '=', 'respuestas.name')
            ->where('municipios_id', $id_municipio)
            ->where('respuestas.id_tramite', $folio)
            ->whereNull('campos.condicion_dependencia')
            ->select('campos.*', 'requisitos.*', 'respuestas.value')
            ->paginate(100);

        $usersData = DB::table('campos')
            ->orderby('secuencia', 'asc')
            ->orderby('campos.id', 'asc')
            ->where('campos.campo_estatico', 1)
            ->select('campos.*')
            ->paginate(100);

        $usersDataRespuestas = DB::table('respuestas')
            ->where('respuestas.id_tramite', $folio)
            ->whereIn('respuestas.name', ['anexo_1', 'anexo_3', 'anexo_4', 'anexo_2'])
            ->select('respuestas.name', 'respuestas.value')
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
    public function find(int $id): ?Campo
    {
        return Campo::find($id);
    }
    function base64_decode_if_needed($str)
    {
        $decoded = base64_decode($str, true);
        if ($str === base64_encode($decoded)) {
            return $decoded;
        }
        return $str;
    }


    public function store(CampoCreateDto $store): Campo
    {

        $campos2 = $this->base64_decode_if_needed($store->condicion_visible);

        $entry                    = new Campo();
        $entry->name              = $store->name;
        $entry->type              = $store->type;
        $entry->description       = $store->description;
        $entry->description_rec   = $store->description_rec;
        $entry->fundamento        = $store->fundamento;
        $entry->opciones          = $store->opciones == '' ? null : $store->opciones;
        $entry->opciones_desc     = $store->opciones_desc == '' ? null : $store->opciones_desc;
        $entry->secuencia         = $store->secuencia;
        $entry->requerido         = $store->requerido;
        $entry->condicion_visible = $campos2;
        $entry->condicion_dependencia = $store->condicion_dependencia == '' ? null : $store->condicion_dependencia;
        $entry->condicion_giro    = $store->condicion_giro == '' ? null : $store->condicion_giro;
        $entry->campo_afectado    = $store->campo_afectado == '' ? null : $store->campo_afectado;
        $entry->tipo_tramite      = $store->tipo_tramite;
        $entry->status            = $store->status;
        $entry->step              = $store->step;
        $entry->id_municipio      = $store->id_municipio;
        $entry->save();
        $entry_requisitos                 = new Requisito();
        $entry_requisitos->municipios_id  = $entry->id_municipio;
        $entry_requisitos->campos_id      = $entry->id;
        $entry_requisitos->id_requisitos  = "null";
        $entry_requisitos->save();

        return $entry;
    }

    public function update(CampoUpdateDto $store): void
    {
        $entry                   = Campo::find($store->id);

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

        $entry->save();
    }

    public function camposRequisitos($tipo_tramite)
    {
        return Campo::query()
            ->where('tipo_tramite', 'LIKE', "%{$tipo_tramite}%")
            ->get();
    }

    public function camposRequisitosFile($id_municipio)
    {
        return  DB::table("campos")
            ->join('requisitos', 'campos.id', '=', 'requisitos.campos_id')
            ->select('*')
            ->whereIn('municipios_id', [$id_municipio, 0])
            ->where('requisitos.municipios_id', $id_municipio)
            ->get();
    }

    public function camposRequisitoTramite($id_municipio)
    {
        //return $id_municipio;
        return  DB::table("campos")
            ->join('requisitos', 'campos.id', '=', 'requisitos.campos_id')
            ->select('campos.*')
            //->whereIn('campos.id_municipio',[$id_municipio,0])
            ->whereIn('requisitos.municipios_id', [$id_municipio])
            ->whereIn('campos.tipo_tramite', ['consulta', 'consulta_requisitos'])
            ->orderBy('id', 'asc')
            ->get();
    }

    public function camposRequisitoTramiteConstruccion($id_municipio)
    {
        //return $id_municipio;

        return  DB::table("campos_construccion")
            ->join('requisitos_construccion', 'campos_construccion.id', '=', 'requisitos_construccion.campos_id')
            ->select('campos_construccion.*')
            //->whereIn('campos_construccionid_municipio',[$id_municipio,0])
            ->whereIn('requisitos_construccion.municipios_id', [$id_municipio])
            ->whereIn('campos_construccion.tipo_tramite', ['consulta', 'consulta_requisitos'])
            ->orderBy('id', 'asc')
            ->get();
    }

    public function camposRequisitoTramiteConstruccion3($id_municipio, $id)
    {
        //return $id_municipio;
        
        return  DB::table("campos_construccion")
            ->where('campos_construccion.type', 'boolean')
            ->where('campos_construccion.tramite_relacionado', $id)
            ->where('campos_construccion.id_municipio', $id_municipio)
            ->join('requisitos_construccion', 'campos_construccion.id', '=', 'requisitos_construccion.campos_id')
            ->select('campos_construccion.*')
            //->whereIn('campos_construccionid_municipio',[$id_municipio,0])
            ->whereIn('requisitos_construccion.municipios_id', [$id_municipio])
            ->whereIn('campos_construccion.tipo_tramite', ['consulta', 'consulta_requisitos'])
            ->orderBy('id', 'asc')
            ->get();
    }

    public function camposRequisitoTramiteConstruccion2($id_municipio, $id)
    {
            return  DB::table("campos_construccion")
            ->where('type', '!=', 'input')
            ->where('id_municipio', $id_municipio)
            ->where('tramite_relacionado', $id)
            ->Orwhere('tramite_relacionado', 0)
            ->join('requisitos_construccion', 'campos_construccion.id', '=', 'requisitos_construccion.campos_id')
            ->select('campos_construccion.*')
            //->whereIn('campos_construccionid_municipio',[$id_municipio,0])
            ->whereIn('requisitos_construccion.municipios_id', [$id_municipio])
            //->whereIn('campos_construccion.tipo_tramite', ['', 'consulta_requisitos'])
            ->orderBy('id', 'asc')
            ->get();
    }

    public function destroy(int $id): void
    {
        Campo::destroy($id);
    }
    public function agregarRequisito($id_municipio, $campo_id, $idRequisito): void
    {
        if ($idRequisito == 0) {
            $entry_requisitos                 = new Requisito();
            $entry_requisitos->municipios_id  = $id_municipio;
            $entry_requisitos->campos_id      = $campo_id;
            $entry_requisitos->id_requisitos  = "null";
            $entry_requisitos->save();
        } else {
            Requisito::destroy($idRequisito);
        }
    }
}
