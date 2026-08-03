<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\SubRole;

class SubRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('subroles')->insert(['nombre' => 'sub admin','descripcion' => 'desc 5']);
        DB::table('subroles')->insert(['nombre' => 'sub ventanilla','descripcion' => 'desc 2']);
        DB::table('subroles')->insert(['nombre' => 'sub revisor','descripcion' => 'desc 3']);
        DB::table('subroles')->insert(['nombre' => 'sub director','descripcion' => 'desc 4']);
        DB::table('subroles')->insert(['nombre' => 'sub ciudadano','descripcion' => 'desc 1']);
    }
}
