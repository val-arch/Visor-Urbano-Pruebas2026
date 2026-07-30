<?php
namespace App\Repositories;

use App\DTOs\Municipios\MunicipioCreateDto;
use App\DTOs\Municipios\MunicipioUpdateDto;
use Exception;
use App\Models\Municipio;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Repositories\Interfaces\IMunicipioRepository;
use Illuminate\Support\Facades\DB;

use function public_path;

class MunicipioRepository implements IMunicipioRepository
{
    public function paginate(int $take,string $order,string $filter): LengthAwarePaginator
    {
        $posts     = json_decode(stripslashes($order));
        $direction = null;
        if(isset($posts->direction)){
            $direction = $posts->direction;
        }
        if($direction== null){
            $columnar  = 'id';
            $direction = 'ASC';
        }else{
            $columnar  = $posts->active;
            $direction = $posts->direction;
        }
        $data =DB::table('municipios')
            ->where(function($query) use ($filter) {
            $query->whereRaw("upper(unaccent(nombre)) LIKE '%".strtoupper($filter)."%'");
                // ->orWhere('director', 'LIKE', '%'.$filter.'%');
            })->orderBy($columnar, $direction);

            $data->image = '¿';


        return  $data->paginate( $take);;
    }

    public function paginate2(int $take,string $order,string $filter): LengthAwarePaginator
    {
        $posts     = json_decode(stripslashes($order));
        $direction = null;
        if(isset($posts->direction)){
            $direction = $posts->direction;
        }
        if($direction== null){
            $columnar  = 'id';
            $direction = 'ASC';
        }else{
            $columnar  = $posts->active;
            $direction = $posts->direction;
        }
        $data =DB::table('municipios_construccion')
            ->where(function($query) use ($filter) {
            $query->whereRaw("upper(unaccent(nombre)) LIKE '%".strtoupper($filter)."%'");
                // ->orWhere('director', 'LIKE', '%'.$filter.'%');
            })->orderBy($columnar, $direction);

            $data->image = '¿';


        return  $data->paginate( $take);;
    }

    public function find(int $id): ? Municipio
    {
        return Municipio::find($id);
    }
    public function findMunicipio(int $municipio): ? Municipio
    {
        return Municipio::find($municipio);
    }
    public function store(MunicipioCreateDto $store): Municipio
    {
        $entry              = new Municipio();
        $entry->nombre      = $store->nombre;
        $entry->director    = $store->director;
        $entry->direccion   = $store->direccion;
        $entry->telefono    = $store->telefono;
        $entry->url_municipio    = $store->$url_municipio;
        $entry->color_hex    = $store->$color_hex;
        $entry->save();
        return $entry;
    }
    public function update(MunicipioUpdateDto $store): void
    {
        $entry                 = Municipio::find($store->id);
        // $entry->nombre         = $store->nombre;
        $entry->director       = $store->director;
        $entry->ficha_tramite  = $store->ficha_tramite;
        $entry->dias_solventar = $store->dias_solventar;
        $entry->direccion      = $store->direccion;
        $entry->telefono       = $store->telefono;
        $entry->precio_lic     = $store->precio_lic;
        $entry->folio_init     =  $store->folio_init;
        $entry->correo_dependencia       = $store->correo_dependencia;
        $entry['emitir_licencia'] = $store->licencias_enlinea;
        $entry->generar_licencia_ventanilla = $store->generar_licencia_ventanilla;
        $entry->area_encargada       = $store->area_encargada;
        $entry->restricciones_licencia       = $store->restricciones_licencia;
        $entry->url_municipio       = $store->url_municipio;
        $entry->color_hex       = $store->color_hex;
        $entry->save();
    }

    public function image(int $id, UploadedFile $file): void
{
    // Find the entry in the database by ID
    $entry = Municipio::find($id);
    if (!$entry) {
        // Throw an exception if the entry is not found
        throw new Exception("Entry wasn't found by " . $id);
    }
    
    // Generate a random filename with the original extension of the uploaded file
    $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();

    // Define the path to save the file in the public images directory
    $path = app()->basePath('public/images/'); // This path should already exist and have the correct permissions

    // Move the uploaded file to the specified path
    try {
        $file->move($path, $filename);
    } catch (\Exception $e) {
        // Handle any exceptions during the file move operation
        throw new Exception("File could not be moved due to: " . $e->getMessage());
    }

    // Update the image URL in the database entry
    $entry->image = env('APP_URL').'images/'.$filename;

    // Save the updated entry
    $entry->save();
}

  /*  public function image(int $id, UploadedFile $file): void
    {
        $entry = Municipio::find($id);
        if ($entry) {
            // filename
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            // path
            $path     = app()->basePath('public/images/');
            chmod(public_path($path), 0777);
            // upload
            $file->move($path, $filename);
            $entry->image = URL::to('images/' . $filename);
            // save changes
            $entry->save();
        } else {
            throw new Exception("Entry wasn't found by " . $id);
        }
    }*/
    public function firma(int $id, UploadedFile $file): void
    {
        $entry = Municipio::find($id);
        if ($entry) {
            // filename
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            // path
            $path     = app()->basePath('public/images/municipio/');
            // upload
            $file->move($path, $filename);
            $entry->firma_director = URL::to('images/municipio/' . $filename);
            // save changes
            $entry->save();
        } else {
            throw new Exception("Entry wasn't found by " . $id);
        }
    }
    public function destroy(int $id): void
    {
        Municipio::destroy($id);
    }
}
