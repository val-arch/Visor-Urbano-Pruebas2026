<?php
namespace App\Repositories;

use App\DTOs\SubRoles\SubRoleCreateDto;
use App\DTOs\SubRoles\SubRoleUpdateDto;
use App\Models\Subrole;
use App\Repositories\Interfaces\ISubRoleRepository;
use Illuminate\Pagination\Paginator;
use App\Traits\ApiResponser;

class SubRoleRepository implements ISubRoleRepository
{
    use ApiResponser;
    public function paginate(int $take): Paginator
    {
        return Subrole::orderBy('nombre')
            ->simplePaginate($take);
    }
   public function paginateMunucipio(int $take, int $id_municipio): Paginator
   {
              return Subrole::orderBy('nombre')
                    ->where('id_municipio',$id_municipio)
                    ->simplePaginate($take);
       
   }
    public function find(int $id): ?Subrole
    {
        return Subrole::find($id);
    }
    public function store(SubRoleCreateDto $store): Subrole
    {
      
        $entry               = new Subrole();
        $entry->nombre       = $store->nombre;
        $entry->descripcion  = $store->descripcion;
        $entry->id_municipio = $store->id_municipio;
        $entry->save();

        return $entry;
    }
    public function update(SubRoleUpdateDto $store): void
    {
        $entry       = Subrole::find($store->id);
        $entry->nombre = $store->nombre;
        $entry->save();    
    }
    public function destroy(int $id): void
    {
        Subrole::destroy($id);
    }   
}
