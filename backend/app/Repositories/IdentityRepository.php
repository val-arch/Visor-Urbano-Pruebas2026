<?php
namespace App\Repositories;

use App\Models\Role;
use App\Models\UserRole;
use App\Models\UserRoleConstruccion;
use App\Models\Municipio;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Interfaces\IIdentityRepository;
use App\Repositories\Exceptions\AccessDeniedException;

class IdentityRepository implements IIdentityRepository
{
    public function signin(string $email, string $password): array
    {
        // find user by email
        $entry = User::where('email', strtolower($email))->first();
        if ($entry) {
            // compare password
            if (Hash::check($password, $entry->password)) {
                $entry->api_token            = Str::random(100);
                $entry->api_token_expiration = Carbon::now()->addDays(5);

                $entry->save();

                if($entry->user_type == 0){
                    if(isset($entry->roles[0])){
                        $role_name = $entry->roles[0]->name;
                        $role_id   = $entry->roles[0]->id;
                    }else{
                        $role_name = null;
                        $role_id   = null;
                    }
                }else if($entry->user_type == 1){
                    if(isset($entry->roles_construccion[0])){
                        $role_name = $entry->roles_construccion[0]->name;
                        $role_id   = $entry->roles_construccion[0]->id;
                    }else{
                        $role_name = null;
                        $role_id   = null;
                    }
                }

                return [
                    'access_token' => $entry->api_token,
                    'expiration'   => $entry->api_token_expiration,
                    'user' => [
                        'id'        => $entry->id,
                        'name'      => $entry->name,
                        'email'     => $entry->email,
                        'user_type' => $entry->user_type,
                        'role_name' => $role_name,
                        'role_id'   => $role_id,
                        'id_municipio'   => $entry->id_municipio,
                        'image_municipio'   => Municipio::find($entry->id_municipio)->image ?? '',
                    ],
                ];

            }
        }
        throw new AccessDeniedException('Acceso denegado');
    }

    public function store($input){
       // return $input;
        $input['password'] = Hash::make($input['password']);
        $entry = User::where('email', $input['email'])->first();
        //var_dump($input);
        //return $input;
        if ($entry) {
            // compare password
            throw new AccessDeniedException('Usuario existente');
        }else{
            $user             = new User;
            $user->name       = $input['name'];
            $user->apellido_p = $input['apellido_p'];
            $user->apellido_m = $input['apellido_m'];
            $user->celular    = $input['celular'];
            $user->curp       = $input['curp'];
            $user->email      = strtolower($input['email']);
            $user->password   = $input['password'];
            $user->id_municipio   = 0;
            $user->save();

            $role = new UserRole();
            $role->user_id = $user->id;
            $role->role_id = 1;
            $role->save();

           /* $roleCons = new UserRoleConstruccion();
            $roleCons->user_id = $user->id;
            $roleCons->role_id = 1;
            $roleCons->save();*/


            return [
                'res'     => true,
                'message' => 'Registro insertado correctamente'
            ];
        }

        throw new AccessDeniedException('Acceso denegado');
    }


}
