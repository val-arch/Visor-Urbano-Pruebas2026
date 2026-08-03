<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequisitoCustomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // todos los municipios
        for ($i=1; $i <= 125 ; $i++) { 
            DB::table('requisitos')->insert(
                [
                    ['municipios_id'=>$i,'campos_id'=> 388],
                ]);
            }   
    }
}
