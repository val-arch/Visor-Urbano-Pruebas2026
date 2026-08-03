<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CampoSeederUpdate extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

     $id = DB::table('campos')->insertGetId(
        [
            'name' => 'telefono_propietario',
            'type' => 'input',
            'description' => 'Teléfono del propietario',
            'description_rec' => '|+|',
            'fundamento' => '',
            'opciones' => '',
            'opciones_desc' => '',
            'step' => 2 ,
            'secuencia' =>6,
            'requerido' =>2 ,
            'condicion_visible' => '/propietario_rad=="propietario_i" || /arrendatario_rad=="arrendatario_i"',
            'campo_afectado' => '',
            'tipo_tramite' => 'oficial',
            'status' => 1,
            'id_municipio'=>0,
            'editable'=>0,
            'campo_estatico'=>0
            ,'requerido_funcionario'=>1
        ],



    );
    $id2 = DB::table('campos')->insertGetId(
        [
            'name' => 'Telefono del arrendatario',
            'type' => 'input',
            'description' => 'Teléfono del arrendatario',
            'description_rec' => '|+|',
            'fundamento' => '',
            'opciones' => '',
            'opciones_desc' => '',
            'step' => 1 ,
            'secuencia' =>6,
            'requerido' =>2 ,
            'condicion_visible' => '/arrendatario_rad=="arrendatario_i"',
            'campo_afectado' => '',
            'tipo_tramite' => 'oficial',
            'status' => 1,
            'id_municipio'=>0,
            'editable'=>0,
            'campo_estatico'=>0
            ,'requerido_funcionario'=>1
        ],
);




$id3 = DB::table('campos')->insertGetId(
    [
        'name' => 'telefono_apoderado',
        'type' => 'input',
        'description' => 'Teléfono del apoderado',
        'description_rec' => '|+|',
        'fundamento' => '',
        'opciones' => '',
        'opciones_desc' => '',
        'step' => 1 ,
        'secuencia' =>12,
        'requerido' =>2 ,
        'condicion_visible' => '/carta_poder_rad=="persona_fisica" || /carta_poder_rad=="persona_moral"',
        'campo_afectado' => '',
        'tipo_tramite' => 'oficial',
        'status' => 1,
        'id_municipio'=>0,
        'editable'=>0,
        'campo_estatico'=>0
        ,'requerido_funcionario'=>1
    ],
    );


    $id4 = DB::table('campos')->insertGetId(
        [

                'name' => 'telefono_dueno_negocio',
                'type' => 'input',
                'description' => 'Teléfono del dueño del negocio',
                'description_rec' => '|+|',
                'fundamento' => '',
                'opciones' => '',
                'opciones_desc' => '',
                'step' => 1 ,
                'secuencia' =>1,
                'requerido' =>2 ,
                'condicion_visible' => '/carta_poder_rad=="persona_fisica"',
                'campo_afectado' => '',
                'tipo_tramite' => 'oficial',
                'status' => 1,
                'id_municipio'=>0,
                'editable'=>0,
                'campo_estatico'=>0
                ,'requerido_funcionario'=>1
            ],
        );



   for ($i=1; $i <= 1 ; $i++) {
        DB::table('requisitos')->insert(
            ['municipios_id'=>$i,'campos_id'=> $id],
        );
    }
    for ($i=1; $i <= 1 ; $i++) {
        DB::table('requisitos')->insert(
                ['municipios_id'=>$i,'campos_id'=> $id2],
            );
    }
    for ($i=1; $i <= 1 ; $i++) {
        DB::table('requisitos')->insert(
                ['municipios_id'=>$i,'campos_id'=> $id3],
            );
    }
    for ($i=1; $i <= 1 ; $i++) {
        DB::table('requisitos')->insert(
                ['municipios_id'=>$i,'campos_id'=> $id4],
            );
    }

 }

}
