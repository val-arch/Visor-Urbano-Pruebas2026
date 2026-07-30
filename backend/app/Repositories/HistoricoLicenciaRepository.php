<?php
namespace App\Repositories;

use App\DTOs\Historico\HistoricoUpdateDto;
use App\Models\HistoricoLicencias;
use App\Repositories\Interfaces\IHistoricoLicenciaRepository;

use Illuminate\Pagination\Paginator;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;

class HistoricoLicenciaRepository implements IHistoricoLicenciaRepository
{
    use ApiResponser;
    public function paginate(int $take ,int $id_municipio,string $filter)
    { 
        $take= 20;
        
        $valor = "";
        if($filter != ""){
            $valor =  $filter;
        }
        if($filter!=''){
        
            return  DB::table('historico_licencias_giro') 
           // ->selectRaw("historico_licencias_giro.*,to_date(fecha_emision,'dd/mm/yyyy') da")
            ->orderByRaw('LENGTH(folio_licencia) asc')
            ->orderBy('folio_licencia', 'ASC')
            ->orderByRaw('anio_licencia DESC NULLS LAST')
            //->orderBy('da', 'DESC')
            ->where('status', '=', 1)
            ->whereNull('deleted_at')
            ->where('id_municipio', '=', $id_municipio)
            
            ->where(function ($query) use ($filter) {
                $query
                ->OrwhereRaw('LOWER(giro) ILIKE (?) ',["%{$filter}%"])
                ->OrwhereRaw('LOWER(descripcion_detallada) ILIKE (?) ',["%{$filter}%"])
                ->OrwhereRaw('LOWER(folio_licencia) ILIKE (?) ',["%{$filter}%"])
                ->OrwhereRaw('LOWER(razon_social) ILIKE (?) ',["%{$filter}%"])
                ->OrwhereRaw("LOWER(calle_predio || ' ' || num_ext_predio || ' ' ||  colonia_predio ) ILIKE (?) ",["%{$filter}%"])
                ->OrwhereRaw("LOWER(nombre_titular || ' ' || apellido_p   || ' ' || apellido_m ) ILIKE (?) ",["%{$filter}%"]);       
                })
           
            ->paginate($take);

           
        }else{
            return  DB::table('historico_licencias_giro') 
          //  ->selectRaw("historico_licencias_giro.*,to_date(fecha_emision,'dd/mm/yyyy') da")
          ->orderByRaw('LENGTH(folio_licencia) asc')
          ->orderBy('folio_licencia', 'ASC')
          ->orderByRaw('anio_licencia DESC NULLS LAST')
       
            //->orderBy('da', 'DESC')
            ->where('status', '=', 1)
            ->whereNull('deleted_at')
            ->where('id_municipio', '=', $id_municipio)
            ->paginate($take);
        }
    }

    public function delete(int $id): void
    {
        $entry           = HistoricoLicencias::find($id);
        $entry->status   = 0;
        $entry->save();
    }

    public function update(HistoricoUpdateDto $store): void
    {
        $entry = HistoricoLicencias::find($store->id);
        $entry->folio_licencia         = $store->folio_licencia;
        $entry->fecha_emision          = $store->fecha_emision;
        $entry->giro                   = $store->giro;
        $entry->descripcion_detallada  = $store->descripcion_detallada;
        $entry->codigo_giro            = $store->codigo_giro;
        $entry->superficie_giro        = $store->superficie_giro;
        $entry->calle                  = $store->calle;
        $entry->numero_ext             = $store->numero_ext;
        $entry->numero_int             = $store->numero_int;
        $entry->colonia                = $store->colonia;
        $entry->clave_catastral        = $store->clave_catastral;
        $entry->referencia             = $store->referencia;
        $entry->coordonadas_x          = $store->coordonadas_x;
        $entry->coordonadas_y          = $store->coordonadas_y;
        $entry->nombre_titular         = $store->nombre_titular;
        $entry->apellido_p             = $store->apellido_p;
        $entry->apellido_m             = $store->apellido_m;
        $entry->rfc                    = $store->rfc;
        $entry->curp                   = $store-> curp;
        $entry->telefono               = $store->telefono;
        $entry->razon_social           = $store->razon_social;
        $entry->email                  = $store-> email;
        $entry->calle_titular          = $store->calle_titular;
        $entry->numero_ext_titular     = $store->numero_ext_titular;
        $entry->numero_int_titular     = $store->numero_int_titular;
        $entry->colonia_titular        = $store-> colonia_titular;
        $entry->venta_alcohol          = $store->venta_alcohol;
        $entry->horario                = $store-> horario;
        $entry->save();
    }  
}