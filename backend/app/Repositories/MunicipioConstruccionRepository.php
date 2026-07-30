<?php

namespace App\Repositories;

use App\DTOs\MunicipiosConstruccion\MunicipioCreateDto;
use App\DTOs\MunicipiosConstruccion\MunicipioUpdateDto;
use Exception;
use App\Models\MunicipioConstruccion;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\FirmaMunicipioConstruccion;

use App\Repositories\Interfaces\IMunicipioConstruccionRepository;
use Illuminate\Support\Facades\DB;

class MunicipioConstruccionRepository implements IMunicipioConstruccionRepository
{
    public function paginate(int $take, string $order, string $filter): LengthAwarePaginator
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
        $data = DB::table('municipios_construccion')
            ->where(function ($query) use ($filter) {
                $query->whereRaw("upper(unaccent(nombre)) LIKE '%" . strtoupper($filter) . "%'");
                // ->orWhere('director', 'LIKE', '%'.$filter.'%');
            })->orderBy($columnar, $direction);

        $data->image = '¿';


        return  $data->paginate($take);;
    }

    public function find(int $id): ?MunicipioConstruccion
    {
        return MunicipioConstruccion::find($id);
    }
    public function findMunicipio(int $municipio): ?MunicipioConstruccion
    {   
        return MunicipioConstruccion::find($municipio);
    }
    public function store(MunicipioCreateDto $store): MunicipioConstruccion
    {
        $entry              = new MunicipioConstruccion();
        $entry->nombre      = $store->nombre;
        $entry->director    = $store->director;
        $entry->direccion   = $store->direccion;
        $entry->telefono    = $store->telefono;
        $entry->save();
        return $entry;
    }
    public function update(MunicipioUpdateDto $store): void
    {
        $entry                 = MunicipioConstruccion::find($store->id);
        //  $entry->nombre         = $store->nombre;
        $entry->director       = $store->director;
        $entry->ficha_tramite  = $store->ficha_tramite;
        $entry->dias_solventar = $store->dias_solventar;
        $entry->direccion      = $store->direccion;
        $entry->telefono       = $store->telefono;
        $entry->correo_dependencia       = $store->correo_dependencia;
        $entry['emitir_licencia'] = $store->licencias_enlinea;
        $entry->generar_licencia_ventanilla = $store->generar_licencia_ventanilla;
        $entry->area_encargada       = $store->area_encargada;
        $entry->usuarios_emision       = $store->usuarios_emision;
        $entry->restricciones_licencia       = $store->restricciones_licencia;
        $entry->save();
    }

    public function image(int $id, UploadedFile $file): void
    {
        $entry = MunicipioConstruccion::find($id);
        if ($entry) {
            // filename
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            // path
            $path     = app()->basePath('public/images/');
            // upload
            $file->move($path, $filename);
            $entry->image = URL::to('images/' . $filename);
            // save changes
            $entry->save();
        } else {
            throw new Exception("Entry wasn't found by " . $id);
        }
    }
    public function firma(int $id, UploadedFile $file): void
    {
        $entry = FirmaMunicipioConstruccion::find($id);
        if ($entry) {
            // filename
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            // path
            $path     = app()->basePath('public/images/municipio/'.$entry->id_municipio.'/');
            // upload
            $file->move($path, $filename);
            $entry->firma = URL::to('images/municipio/' .$entry->id_municipio.'/'.$filename);
            // save changes
            $entry->save();
        } else {
            throw new Exception("Entry wasn't found by " . $id);
        }
    }
    public function destroy(int $id): void
    {
        MunicipioConstruccion::destroy($id);
    }
}
