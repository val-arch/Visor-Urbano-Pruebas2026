<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MunicipioConstruccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('municipios_construccion')->insert(
            [
                ['created_at'=>date('Y-m-d H:m:s'), "nombre"=> "Cuernavaca"],
            ]
        );
    }
}
