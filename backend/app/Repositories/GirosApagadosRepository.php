<?php

namespace App\Repositories;

use App\Models\GiroApagado;
use App\Models\CedulaGiro;
use App\Models\GirosImpacto;
use App\DTOs\GirosApagados\GirosApagadosCreateDto;
use App\Models\GirosConfiguracion;
use App\Repositories\Interfaces\IGirosApagadosRepository;
use App\Traits\ApiResponser;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Assert;

class GirosApagadosRepository implements IGirosApagadosRepository
{
    use ApiResponser;
    public function store(GirosApagadosCreateDto $store): GiroApagado
    {
        $buscar = GiroApagado::where(['municipios_id' => $store->municipios_id, 'giros_id' => $store->giros_id])->get();
        if (count($buscar) > 0) {
            return $buscar;
        }
        $entry                = new GiroApagado();
        $entry->giros_id      = $store->giros_id;
        $entry->municipios_id = $store->municipios_id;
        $entry->save();
        return $entry;
    }
    public function storeStatus(GirosApagadosCreateDto $store, $status)
    {
        $entry = GirosConfiguracion::where([
            'municipios_id' => $store->municipios_id,
            'giros_id' => $store->giros_id
        ])->update(['giro_apagado' => $status]);


        return $entry;
    }

    public function storeStatusCedula(GirosApagadosCreateDto $store, $status)
    {

        $entry = GirosConfiguracion::where([
            'municipios_id' => $store->municipios_id,
            'giros_id' => $store->giros_id
        ])->update(['giro_cedula' => $status]);
        return $entry;
    }
    public function destroy(int $id): void
    {
        $giro = GiroApagado::find($id);
        $giro->delete();
    }

    public function storeCedula($data)
    {
        $buscar = CedulaGiro::where(['municipio_id' => $data['municipio_id'], 'giro_id' => $data['giro_id']])->get();
        if (count($buscar) > 0) {
            return $buscar;
        }
        $entry                = new CedulaGiro();
        $entry->giro_id      = $data['giro_id'];
        $entry->municipio_id = $data['municipio_id'];
        $entry->save();
        return $entry;
    }
    public function destroyCedula(int $id): void
    {
        $giro = CedulaGiro::find($id);
        $giro->delete();
    }
    public function storeImpacto($data)
    {

        $entry                = new GirosImpacto();
        $entry->impacto      = $data['impacto'];
        $entry->giro_id      = $data['giro_id'];
        $entry->municipio_id = $data['municipio_id'];
        $entry->save();
        return $entry;
    }
    public function updateImpacto(int $id_municipio, $id_giro, int $impacto)
    {
        $giro = GirosConfiguracion::where(['municipios_id' => $id_municipio, 'giros_id' => $id_giro])->update(['giro_impacto' => $impacto]);
        return $giro;
    }


    public function getGirosOn(int $take, int $municipio): LengthAwarePaginator
    {
        $data = DB::table('giros')
    ->join('giros_configuracion', 'giros.id', '=', 'giros_configuracion.giros_id')
    ->where('giros_configuracion.municipios_id', 1)
    ->where('giros_configuracion.giro_apagado', 1)
    ->whereNull('giros.deleted_at')
    ->select('giros.*')
    ->paginate($take);



        // Convertir los datos a una colección y filtrar los registros donde deleted_at no sea null
        $filteredData = collect($data->items())->filter(function ($item) {
            return is_null($item->deleted_at);
        });

        // Crear un nuevo LengthAwarePaginator con los datos filtrados
        $paginatedData = new LengthAwarePaginator(
            $filteredData, // La colección filtrada
            $data->total(), // El total de registros antes de la filtración
            $take, // El número de registros por página
            $data->currentPage(), // La página actual
            ['path' => LengthAwarePaginator::resolveCurrentPath()] // La ruta actual
        );

        return $paginatedData;
    }

    /**
     * @param int $take
     * @param int $municipio
     * @return \Illuminate\Support\Collection
     */
    public function getGirosPublic(int $take, int $municipio): Collection
    {

        /*  $data=  DB::table("giros")->select('*')->whereNotIn('id', function ($query)  use ($municipio) {
            $query->select('giros_id')->from('giro_apagados')->where('municipios_id', $municipio);
        })->get($take);*/
        if ($municipio != 0) {
            $data = DB::table('giros')
                ->join('giros_configuracion', 'giros.id', '=', 'giros_configuracion.giros_id')
                ->where('giros_configuracion.municipios_id', '=', $municipio)
                ->where('giros_configuracion.giro_apagado', '=', 1)
                ->whereNull('giros.deleted_at')
                ->select('giros.*')
                ->get();
        } else {
            $data = DB::table('giros')
                ->select('id', 'codigo', 'SCIAN')
                ->get();
        }
        return $data;
    }

    public function getGirosAllMapa(int $take, int $municipio, $order): LengthAwarePaginator
    {
        $posts     = json_decode(stripslashes($order));
        $direction = null;
        if (isset($posts->direction)) {
            $direction = $posts->direction;
        }
        if ($direction == null) {
            $columnar  = 'id';
            $direction = 'ASC';
        } else {
            $columnar  = $posts->active;
            $direction = $posts->direction;
        }

        $data = DB::table('giros')
            ->join('giros_configuracion', 'giros_configuracion.giros_id', '=', 'giros.id')
            ->where('giros_configuracion.municipios_id', '=', $municipio)
            ->whereNull('giros.deleted_at')  // Condición para mostrar solo los registros no eliminados
            ->orderBy('giros.' . $columnar, $direction);

        return $data->paginate($take);
    }

    public function getGirosAll(int $take, int $municipio, string $order, string $filter): LengthAwarePaginator
    {
        $posts     = json_decode(stripslashes($order));
        $direction = null;
        if (isset($posts->direction)) {
            $direction = $posts->direction;
        }
        if ($direction == null) {
            $columnar  = 'id';
            $direction = 'ASC';
        } else {
            $columnar  = $posts->active;
            $direction = $posts->direction;
        }

        $data = DB::table('giros')
            ->join('giros_configuracion', 'giros_configuracion.giros_id', '=', 'giros.id')
            ->where('giros_configuracion.municipios_id', '=', $municipio)
            ->whereNull('giros.deleted_at')  // Condición para mostrar solo los registros no eliminados
            ->where(function ($query) use ($filter) {
                $query->where('giros.codigo', 'LIKE', '%' . $filter . '%')
                    ->orWhere('giros.SCIAN', 'ilike', '%' . $filter . '%')
                    ->orWhereRaw('upper(unaccent("giros"."SCIAN")) ilike \'%' . strtoupper($filter) . '%\'');
            })
            ->orderBy('giros.' . $columnar, $direction);

        return $data->paginate($take);
    }



    /* public function getGirosAll(int $take, int $municipio, string $order, string $filter): LengthAwarePaginator
    {

        $posts     = json_decode(stripslashes($order));
        $direction = null;
        if (isset($posts->direction)) {
            $direction = $posts->direction;
        }
        if ($direction == null) {
            $columnar  = 'id';
            $direction = 'ASC';
        } else {
            $columnar  = $posts->active;
            $direction = $posts->direction;
        }
        /*$data=  DB::table('giros')
                //->join('giro_apagados', 'giros.id', '=', 'giro_apagados.giros_id', 'full outer')
                //->where(function($query) use ($municipio) {
                //$query->whereNull('giro_apagados.municipios_id')
                //      ->orWhere('giro_apagados.municipios_id','=',$municipio);
                //})
                ->where(function($query) use ($filter) {
                    $query->where('giros.codigo', 'LIKE', '%'.$filter.'%')
                          ->orWhereRaw('upper(unaccent("giros"."SCIAN")) LIKE \'%'.strtoupper($filter).'%\'');
                          //->orWhereRaw('unaccent("giros"."palabras_relacion")::text LIKE \'%'.$filter.'%\'');
                })
                ->select('giros.id', 'giros.codigo', 'giros.SCIAN',)
                ///->select('giros.id', 'giros.codigo', 'giros.SCIAN','giro_apagados.giros_id','giro_apagados.id as encendido')
                ->orderBy('giros.'.$columnar, $direction);

                $data = $data->addSelect(DB::raw("(select id from giro_apagados d where d.giros_id = giros.id and d.municipios_id = ".$municipio." ) as encendido, ".$municipio."  id_municipio"));
                $data = $data->addSelect(DB::raw("(select id from giros_cedula d where d.giro_id = giros.id and d.municipio_id = ".$municipio." ) as encendidoCedula"));
                $data = $data->addSelect(DB::raw("(select impacto from giros_impacto d where d.giro_id = giros.id and d.municipio_id = ".$municipio." ) as impacto"));

            */

    /*   $data =  DB::table('giros')
                ->join('giros_configuracion', 'giros_configuracion.giros_id', '=', 'giros.id')
                ->where('giros_configuracion.municipios_id', '=', $municipio)
                ->where(function($query) use ($filter) {
                    $query->where('giros.codigo', 'LIKE', '%'.$filter.'%')
                    ->orwhere('giros.SCIAN', 'ilike', '%'.$filter.'%')
                    ->orWhereRaw('upper(unaccent("giros"."SCIAN")) ilike \'%'.strtoupper($filter).'%\'');
                          //->orWhereRaw('unaccent("giros"."palabras_relacion")::text LIKE \'%'.$filter.'%\'');
                })
                ->orderBy('giros.'.$columnar, $direction);
    /*
        $data =  DB::table('giros')
            ->innerJoin('giro_apagados', 'giros.id', '=', 'giro_apagados.giros_id')
            ->leftJoin('giros_impacto', 'giros.id', '=', 'giros_impacto.giro_id')
            ->select(
                'giros.id',
                'giros.codigo',
                'giros.SCIAN',
                'giro_apagados.id as encendido',
                'giros_impacto.impacto'
            )->where(function ($query) use ($municipio) {
                $query->where('giros_impacto.municipio_id', '=', $municipio)
                      ->OrWhereNull('giros_impacto.municipio_id')
                      ->OrWhere('giro_apagados.municipios_id', '=', $municipio)
                      ->OrWhereNull('giro_apagados.municipios_id');
            })
            ->where(function ($query) use ($filter) {
                $query->where('giros.codigo', 'LIKE', '%' . $filter . '%')
                    ->orWhereRaw('upper(unaccent("giros"."SCIAN")) LIKE \'%' . strtoupper($filter) . '%\'');
            })
            ->orderBy('giros.' . $columnar, $direction);


        $data = $data->addSelect(DB::raw("(select id from giros_cedula d where d.giro_id = giros.id and d.municipio_id = " . $municipio . " ) as encendidoCedula," . $municipio . "  id_municipio"));
    */
    /* return  $data->paginate($take);
    }*/

    public function getGirosOff(int $take, int $municipio): LengthAwarePaginator
    {
        return  DB::table("giros")->select('*')->where('id', function ($query) use ($municipio) {
            $query->select('giros_id')->from('giro_apagados')->where('municipios_id', $municipio);
        })->paginate($take);
    }
}
