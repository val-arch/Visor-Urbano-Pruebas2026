<?php
namespace App\Repositories;
use App\Models\Role;
use App\Repositories\Interfaces\IRoleRepository;
use App\DTOs\Roles\RoleCreateDto;
use App\DTOs\roles\RoleUpdateDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RoleRepository implements IRoleRepository
{

    /**
     * @param int $take
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    
    public function paginate(int $take): LengthAwarePaginator
    {
        $roles = Role::withTrashed()
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

    public function paginate2(int $take, int $value): LengthAwarePaginator
    {
        $roles = Role::withTrashed()->where('role_type', $value)
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
  
            $roles = Role::withTrashed()
                ->select('roles.*')
                ->selectRaw('(select id as idr from user_roles as ur 
                              where user_id = '.$id_usuario.' and role_id = roles.id)')
                ->where('deleted_at',null)
                ->where('roles.name','!=','admin')
                ->where('roles.role_type', 0)
                ->where(function($q)  use ($id_municipio){
                    $q->where('id_municipio', $id_municipio)
                      ->orWhere('id_municipio', 0);
               })
              //  ->where('id_municipio',$id_municipio)
               // ->Orwhere('id_municipio',0)
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
    public function find(int $id): ?Role
    {
        return Role::find($id);
    }
    public function store(RoleCreateDto $store, int $value)
    {
        $role = Role::where('name',$store->name)->where('id_municipio',$store->id_municipio)->get();
        if(count($role)==0){
        $entry       = new Role();
        $entry->name = $store->name;
        $entry->descripcion  = $store->descripcion;
        $entry->id_municipio = $store->id_municipio;
        $entry->role_type = $value;
        $entry->save();
        return $entry;               
        }
    return false;
    }
    public function update(RoleUpdateDto $store): void
    {
        $entry               = Role::find($store->id);
        $entry->name         = $store->name;
        $entry->descripcion  = $store->descripcion;
        $entry->save();
    }
    public function destroy(int $id): void
    {
        Role::destroy($id);
    }

    public function getMyRoleMunicipaly($id_municipio){

        return $role = Role::where('id_municipio',$id_municipio)->get();

    }
}