<?php
namespace App\Repositories;
use App\Models\RoleConstruccion;
use App\Repositories\Interfaces\IRoleConstruccionRepository;
use App\DTOs\RolesConstruccion\RoleCreateDto;
use App\DTOs\RolesConstruccion\RoleUpdateDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RoleConstruccionRepository implements IRoleConstruccionRepository
{

    /**
     * @param int $take
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    
    public function paginate(int $take): LengthAwarePaginator
    {
        $roles = RoleConstruccion::withTrashed()
                ->orderBy('name')
                ->paginate($take);

       foreach ($roles as $i => $status) {
           if($roles[$i]->deleted_at){
               $roles[$i]->status = "false";
            }else{
               $roles[$i]->status = "true";
            }
       }
       return $roles;
    }

    public function getAll($id_municipio)
    {
        $roles = RoleConstruccion::where('id_municipio', $id_municipio)
        ->where('roles_construccion.id', '>', 6)
        ->orderBy('name')->get();

        return $roles;
    }



    public function paginate2(int $take, int $value): LengthAwarePaginator
    {
        $roles = RoleConstruccion::withTrashed()->where('role_type', $value)
                ->orderBy('name')
                ->paginate($take);

       foreach ($roles as $i => $status) {
           if($roles[$i]->deleted_at){
               $roles[$i]->status = "false";
            }else{
               $roles[$i]->status = "true";
            }
       }
       return $roles;
    }
   
    public function paginaterole(int $take,int $id_usuario,int $id_municipio): LengthAwarePaginator  {
  
            $roles = RoleConstruccion::withTrashed()
                ->select('roles_construccion.*')
                ->selectRaw('(select id as idr from user_roles_construccion as ur 
                              where user_id = '.$id_usuario.' and role_id = roles_construccion.id)')
                ->where('deleted_at',null)
                ->where('roles_construccion.name','!=','admin')
                ->where(function($q)  use ($id_municipio){
                    $q->where('id_municipio', $id_municipio)
                      ->orWhere('id_municipio', 0);
                })
                //->where('id_municipio', $id_municipio)
                //->orWhere('roles_construccion.id',4)
                ->orderBy('id')
                ->paginate($take);

                
              
                foreach ($roles as $i => $status) {
                    if($roles[$i]->deleted_at){
                        $roles[$i]->status = "false";
                     }else{
                        $roles[$i]->status = "true";
                     }
                     if($roles[$i]->idr>0){
                         $roles[$i]->user_id = true;
                      }else{
                         $roles[$i]->user_id = false;
                      }
         
                }
                return $roles;
    }   
    public function find(int $id): ?RoleConstruccion
    {
        return RoleConstruccion::find($id);
    }
    public function store(RoleCreateDto $store, int $value)
    {
        $role = RoleConstruccion::where('name',$store->name)->where('id_municipio',$store->id_municipio)->get();
        if(count($role)==0){
        $entry       = new RoleConstruccion();
        $entry->name = $store->name;
        $entry->descripcion  = $store->descripcion;
        $entry->id_municipio = $store->id_municipio;
        $entry->save();
        return $entry;               
        }
    return false;
    }
    public function update(RoleUpdateDto $store): void
    {
        $entry               = RoleConstruccion::find($store->id);
        $entry->name         = $store->name;
        $entry->descripcion  = $store->descripcion;
        $entry->save();
    }
    public function destroy(int $id): void
    {
        RoleConstruccion::destroy($id);
    }

    public function getMyRoleMunicipaly($id_municipio){

        return $role = RoleConstruccion::where('id_municipio',$id_municipio)->get();

    }
    public function getUserRole($id){
        return $role = DB::table('user_roles_construccion')->select('id')->where('user_roles_construccion.user_id',$id)->get();

    }
}