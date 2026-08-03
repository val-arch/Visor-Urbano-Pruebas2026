<?php
use App\User;
use App\Models\UserRole;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserPruebaSeeder extends Seeder{
    
    public function run()
    {
        // factory(User::class, 20)->create();
        $muni = DB::table('municipios')->get();
        foreach ($muni as $municipio) {
            # code...
            $nombre = str_replace(' ','-',$municipio->nombre);
            $d1 = array('á','Á','é','É','í','Í','ó','Ó','ú','Ú','ñ');
            $d2 = array('a','A','e','E','i','I','o','O','u','U','n');
            $nombre = str_replace($d1,$d2,$nombre);
            $nombre = strtolower($nombre);
            $this->newUserRole($municipio->id,"director-".$nombre."@visorurbano.com","vis0rUrban0Jalisc02021.", str_replace(' ','-',$municipio->nombre),4);
        }
        // $this->newUserRole(0,'ciudadano@visorurbano.com','visorUrbanoJalisco2021Pruebas.','Ciudadano ',1);
        // $this->newUserRole(1,'ventanilla@visorurbano.com','visorUrbanoJalisco2021Pruebas.','Ventanilla ',2);
        // $this->newUserRole(1,'revisor@visorurbano.com','visorUrbanoJalisco2021Pruebas.','Revisor ',3);
        // $this->newUserRole(1,'director@visorurbano.com','visorUrbanoJalisco2021Pruebas.','Director ',4);
        // $this->newUserRole(0,'sergio@visorurbano.com','visorSergioAdmin2021.','Admin',5);
        // $this->newUserRole(1,'tecnico@visorurbano.com','visorUrbanoJalisco2021Pruebas.','tecnico',6);
        // $this->newUserRole(20,'cabocorrientes@visorurbano.com','cabocorrientes2021.','Cabo Corrientes');

    }

    public function newUserRole($id_municipio,$correo,$contraseña,$nombre,$roleI=4){
        $user             = new User;
        $user->name       = $nombre; 
        $user->apellido_p = 'Visor Urbano'; 
        $user->apellido_m = 'Jalisco'; 
        $user->celular    = '0'; 
        $user->email      = $correo; 
        $user->password   = Hash::make($contraseña);
        $user->id_municipio   = $id_municipio;
        $user->save();

        $role = new UserRole();
        $role->user_id = $user->id;
        $role->role_id = $roleI;
        $role->save();
    }
}