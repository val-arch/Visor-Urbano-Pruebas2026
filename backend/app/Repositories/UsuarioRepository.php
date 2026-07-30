<?php

namespace App\Repositories;

use App\Repositories\Interfaces\IUsuarioRepository;
use App\DTOs\Usuarios\UsuarioCreateDto;
use App\DTOs\Usuarios\UsuarioMunicipioUpdateDto;
use App\DTOs\Usuarios\UsuarioRoleUpdateDto;
use App\DTOs\Usuarios\UsuarioUpdateDto;
use App\Models\UserRoleConstruccion;
use App\Models\UserRole;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Exceptions\AccessDeniedException;
use App\Traits\ApiResponser;
use App\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UsuarioRepository implements IUsuarioRepository
{
    use ApiResponser;
    /**
     * @param int $take
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $take, string $filter): LengthAwarePaginator
    {

        $valor = "";
        if ($filter != "") {
            $valor =  $filter;
        }
        $user =  Auth::user();

        $role = $user->roles[0]->id;

        if ($role == 4 && $filter != '') {
            return $users = Usuario::where('users.email', 'like', '%' . $valor . '%')
                ->wherein('users.id_municipio', [$user->id_municipio, 0])
                ->where('user_type', 0)
                ->where('users.id', '!=', 5)
                ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                ->leftJoin('roles', 'user_roles.role_id', 'roles.id')
                ->select('roles.name as role_name', 'roles.id as id_role','users.*')
                ->paginate($take);
        } else {
            $users = Usuario::where('users.id_municipio', $user->id_municipio)
                ->where('user_type', 0)
                ->where('users.id', '!=', 5)
                ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                ->leftJoin('roles', 'user_roles.role_id', 'roles.id')
                ->where('roles.id', '!=', 1)
                ->select('roles.name as role_name', 'roles.id as id_role','users.*')
                ->paginate($take);

            return $users;
        }
    }
    public function paginate2(int $take, string $filter)
    {

        $valor = "";
        if ($filter != "") {
            $valor =  $filter;
        }
        $user =  Auth::user();
        $role = $user->roles_construccion[0]->id;
        if (($role == 4 || $role == 5) && $filter != '') {
            return $users = Usuario::where('users.email', 'like', '%' . $valor . '%')
                ->wherein('users.id_municipio', [$user->id_municipio, 0])
                ->where('users.id', '!=', 5)
                ->join('user_roles_construccion', 'users.id', '=', 'user_roles_construccion.user_id')
                ->leftJoin('roles_construccion', 'user_roles_construccion.role_id', 'roles_construccion.id')
                ->select('roles_construccion.name as role_name', 'roles_construccion.id as id_role','users.*')
                ->paginate($take);
        } else {
            $users = Usuario::where('users.id_municipio', $user->id_municipio)
                ->where('user_type', 1)
                ->where('users.id', '!=', 5)
                ->join('user_roles_construccion', 'users.id', '=', 'user_roles_construccion.user_id')
                ->leftJoin('roles_construccion', 'user_roles_construccion.role_id', 'roles_construccion.id')
                ->where('roles_construccion.id', '!=', 1)
                ->select('roles_construccion.name as role_name', 'roles_construccion.id as id_role','users.*')
                ->paginate($take);

            return $users;
        }
    }
    public function paginate3(int $take, string $filter)
    {
        $valor = "";
        if ($filter != "") {
            $valor =  $filter;
        }
        $user =  Auth::user();
        $users = DB::table('usuario_construccion');
        $role = $user->roles[0]->id;
        if (($role == 4 || $role == 5) && $filter != '') {
            $users->where('id_municipio', '=', $user->id_municipio)  ->where(function($q) use ($valor){
                $q->where('email', 'ilike', '%'.$valor.'%')
                ->orWhere('name', 'ilike', '%'.$valor.'%');
            });
        } else {
            if ($role != 5) {
                $users->where('id_municipio', '=', $user->id_municipio);
            }
        }
        $users ->select('usuario_construccion.*');
        return     $users->paginate();
    }
    public function paginate4(int $take, string $filter)
    {

        $valor = "";
        if ($filter != "") {
            $valor =  $filter;
        }
        $user =  Auth::user();
        $users = DB::table('usuario_construccion');
        $users->where('email', 'like', '%' . $valor . '%');
        $users->where('user_type', 1);
        $users->where('id_municipio', $user->id_municipio);
        $users->where('role_id', '!=', 4);
        $users->orderBy('email', 'asc');
        $users ->select('usuario_construccion.*');
        return     $users->paginate();


        /*$users = DB::table('users')
            ->where('email', 'like', '%' . $valor . '%')->where('user_type', 1)
            ->whereNull('users.deleted_at')
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->join('roles', 'user_roles.role_id', '=', 'roles.id')
            ->leftJoin('roles as tbl2', 'user_roles.role_id_pendiente', '=', 'tbl2.id')
            ->orderBy('users.email', 'asc')
            ->select('users.*', 'users.name as nombre_user', 'user_roles.role_id', 'user_roles.role_status', 'user_roles.role_id_pendiente', 'roles.name', 'tbl2.name as name2')
            ->where('user_roles.role_id', '!=', 4)
           */

    }
    public function find(int $id): ?Usuario
    {
        $entry = Usuario::find($id);
        if (isset($entry->roles[0]->name)) {
        }
        return $entry;
    }
    public function roleAsignado(int $id)
    {
        $usuario = User::findOrFail($id);
        $data    = User::select('users.id_municipio', 'user_roles.*')
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->where('user_id', '=', $id)
            ->get();

        if ($data[0]->role_id == 1) {
            $usuario->id_role = $usuario->roles[0]->id;
            return $usuario;
        }
        if ($data[0]->role_id == 4) {
            $usuario->id_role = $usuario->roles[0]->id;
            $data = User::select('subroles.*', 'users.*')
                ->join('subroles', 'subroles.id', '=', 'users.subrole_id')
                ->where('subroles.id_municipio', '=',  $usuario->id_municipio)
                ->get($take);

            return $data;
        }
        return "Sin Permisos";
    }
    public function asignarRole(UsuarioRoleUpdateDto $store, int $id_municipio, $token)
    {
        $user =  DB::table('users')->where('id', $store->id)->get();
        $roleAsignado = DB::table('user_roles')->where('user_id', $store->id)->value('role_id');

        if ($store->role_id == 1) {
            $usuario      = DB::table('user_roles')
                ->where('user_id', $store->id)
                ->update(['role_id' => $store->role_id]);
        } else {
            $usuario      = DB::table('user_roles')
                ->where('user_id', $store->id)
                ->update(['role_id_pendiente' => $store->role_id, 'role_status' => 2, 'token' => $token]);
        }

        if ($store->role_id > 1) {
            $usuario               = User::find($store->id);
            $usuario->id_municipio = $id_municipio;
            $usuario->save();
        }
        if ($store->role_id == 1) {
            $usuario               = User::find($store->id);
            $usuario->id_municipio = 0;
            $usuario->save();
        }

        return $user;
    }

    public function asignarRoleConstruccion(UsuarioRoleUpdateDto $store, int $id_municipio, $token)
    {
        $user =  DB::table('users')->where('id', $store->id)->get();
        $roleAsignado = DB::table('user_roles_construccion')->where('user_id', $store->id)->value('role_id');

        if ($store->role_id == 1) {
            $usuario      = DB::table('user_roles_construccion')
                ->where('user_id', $store->id)
                ->update(['role_id' => $store->role_id]);
        } else {
            $usuario      = DB::table('user_roles_construccion')
                ->where('user_id', $store->id)
                ->update(['role_id_pendiente' => $store->role_id, 'role_status' => 2, 'token' => $token]);
        }

        if ($store->role_id > 1) {
            $usuario               = User::find($store->id);
            $usuario->id_municipio = $id_municipio;
            $usuario->save();
        }
        if ($store->role_id == 1) {
            $usuario               = User::find($store->id);
            $usuario->id_municipio = 0;
            $usuario->save();
        }

        return $user;
    }

    public function asignarSubRole(int $id_usuario, int $id_subrole)
    {
        $usuario             = User::find($id_usuario);
        $usuario->subrole_id =  $id_subrole;
        $usuario->save();
        return true;
    }

    public function quitarSubRole(int $id_usuario)
    {
        $usuario             = User::find($id_usuario);
        $usuario->subrole_id =  null;
        $usuario->save();
        return true;
    }

    public function quitarRole(int $id): void
    {
        $user = UserRole::where('user_id', $id)->update(['role_id' => 1, 'role_id_pendiente' => null, 'role_status' => null, 'token' => null]);
    }

    public function quitarRoleConstruccion(int $id): void
    {
        $user = UserRoleConstruccion::where('user_id', $id)->update(['role_id' => 1, 'role_id_pendiente' => null, 'role_status' => null, 'token' => null]);
    }
    public function store(UsuarioCreateDto $store, int $value)
    {

        $entry = new Usuario();
        $entryUser = Usuario::where('email', $store->email)->first();
        if ($entryUser) {
            // compare password
        } else {
            $entry->name       = $store->name;
            $entry->apellido_p = $store->apellido_p;
            $entry->apellido_m = $store->apellido_m;
            $entry->rfc        = $store->rfc;
            $entry->curp       = $store->curp;
            $entry->celular    = $store->celular;
            $entry->email      = $store->email;
            $entry->user_type  = 0;
            $entry->id_municipio  = Auth::user()->id_municipio;
            $entry->password   = Hash::make($store->password);

            $entry->save();


            $role          = new UserRole();
            $role->user_id = $entry->id;
            $role->role_id = $store->role;
            $role->save();

            return $entry;
        }
    }

    public function update(UsuarioUpdateDto $store): void
    {
        $entry = Usuario::find($store->id);
        $entry->name       = $store->name;
        $entry->apellido_p = $store->apellido_p;
        $entry->apellido_m = $store->apellido_m;
        $entry->rfc        = $store->rfc;
        $entry->curp       = $store->curp;
        $entry->celular    = $store->celular;
        $entry->email      = $store->email;
        $entry->password   = Hash::make($store->password);
        $entry->save();
    }

    public function updateMunicipio(UsuarioMunicipioUpdateDto $store): void
    {

        $entry = Usuario::find($store->id);
        $entry->id_municipio = $store->id_municipio;
        $entry->save();
    }


    public function destroy(int $id): void
    {
        Usuario::destroy($id);
    }

    public function storeConstruccion(UsuarioCreateDto $store, int $value)
    {

        $entry = new Usuario();
        $entryUser = Usuario::where('email', $store->email)->first();
        if ($entryUser) {
            // compare password
        } else {
            $entry->name       = $store->name;
            $entry->apellido_p = $store->apellido_p;
            $entry->apellido_m = $store->apellido_m;
            $entry->rfc        = $store->rfc;
            $entry->curp       = $store->curp;
            $entry->celular    = $store->celular;
            $entry->email      = $store->email;
            $entry->user_type  = 1;
            $entry->id_municipio  = Auth::user()->id_municipio;
            $entry->password   = Hash::make($store->password);

            $entry->save();


            $role          = new UserRoleConstruccion();
            $role->user_id = $entry->id;
            $role->role_id = $store->role;
            $role->save();

            return $entry;
        }
    }
}
