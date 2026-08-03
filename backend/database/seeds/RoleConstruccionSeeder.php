<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleConstruccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles_construccion')->insert(['name' => 'ciudadano','id_municipio' => 0]);
        DB::table('roles_construccion')->insert(['name' => 'ventanilla','id_municipio' => 0]);
        DB::table('roles_construccion')->insert(['name' => 'revisor','id_municipio' => 0]);
        DB::table('roles_construccion')->insert(['name' => 'director','id_municipio' => 0]);
        DB::table('roles_construccion')->insert(['name' => 'admin','id_municipio' => 0]);
        DB::table('roles_construccion')->insert(['name' => 'tecnico','id_municipio' => 0]);

    }
}
