<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CampoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
			
        DB::table('campos')->insert(
            [
                [
                    'name' => 'quien_tramita',
                    'type' => 'radio',
                    'description' => '¿Quién tramita?', 
                    'description_rec' => '', 
                    'fundamento' => '', 
                    'opciones' => 'Propietario(Directamente)| Arrendatario (Directamente)| Me otorgaron una carta poder simple ante 2 testigos para tramitar',
                    'opciones_desc' => 'propietario|arrendatario|carta_poder',
                    'step' => 1,
                    'secuencia' => 0, 
                    'requerido' => 1, 
                    'condicion_visible' => '', 
                    'campo_afectado' => 'propietario_rad,arrendatario_rad,carta_poder_rad', 
                    'tipo_tramite' => 'consulta',
                    'status' => 1, 
                    'id_municipio'=>0
                    ,'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],
                
                [
                    'name' => 'propietario_rad',
                    'type' => 'radio',
                     'description' => 'Propietario (Directamente)',
                     'description_rec' => '',
                    'fundamento' => '',
                     'opciones' => 'Soy propietario del inmueble| Soy representante de una persona moral que es la propietaria del inmueble',
                    'opciones_desc' => 'propietario_i|representante_j',
                     'step' => 1,
                    'secuencia' => 1, 
                    'requerido' => 3,
                    'condicion_visible' => 'propietario',
                     'campo_afectado' => '',
                     'tipo_tramite' => 'consulta',
                    'status' => 1, 
                    'id_municipio'=>0
                    ,'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],

                [
                    'name' => 'arrendatario_rad',
                     'type' => 'radio',
                     'description' => 'Arrendatario (Directamente)',
                    'description_rec' => ',,Prueba comentario',
                     'fundamento' => '',
                    'opciones' => 'Soy arrendatario del inmueble| Soy representante de una persona moral que es la arrendataria del inmueble',
                    'opciones_desc' => 'arrendatario_i|representante_arr',
                     'step' => 1,
                    'secuencia' => 1, 
                    'requerido' =>3, 
                    'condicion_visible' => 'arrendatario',
                    'campo_afectado' => '',
                     'tipo_tramite' => 'consulta',
                     'status' => 1, 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],

                [
                    'name' => 'carta_poder_rad',
                     'type' => 'radio',
                     'description' => 'Me otorgaron una carta poder ante 2 testigos para tramitar',
                    'description_rec' => '',
                     'fundamento' => '',
                     'opciones' => 'El dueño del negocio va a ser persona física| El dueño del negocio va a ser persona moral (Empresa)', 
                    'opciones_desc' => 'persona_fisica|persona_moral',
                     'step' => 1,
                    'secuencia' => 1, 
                    'requerido' => 3, 
                    'condicion_visible' => 'carta_poder', 
                    'campo_afectado' => '',
                     'tipo_tramite' => 'consulta',
                     'status' => 1, 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],

                [
                    'name' => 'identificacion_propietario',
                     'type' => 'file',
                     'description' => 'Identificación del propietario', 
                    'description_rec' => '',
                     'fundamento' => '',
                     'opciones' => '', 
                    'opciones_desc' => '',
                     'step' => 2,
                    'secuencia' => 50, 
                    'requerido' => 1, 
                    'condicion_visible' => '',
                    'campo_afectado' => '',
                     'tipo_tramite' => 'oficial',
                     'status' => 1, 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],

                [
                    'name' => 'propiedad_inmueble',
                    'type' => 'file', 
                    'description' => 'Documento que acredite la propiedad del inmueble (Escritura, título de propiedad, cesión de derechos, etc.)',
                    'description_rec' => '',
                     'fundamento' => '',
                     'opciones' => '', 
                    'opciones_desc' => '',
                     'step' => 2,
                    'secuencia' => 51, 
                    'requerido' => 1, 
                    'condicion_visible' => '', 
                    'campo_afectado' => '',
                     'tipo_tramite' => 'oficial',
                     'status' => 1, 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],

                [
                    'name' => 'predial',
                     'type' => 'file', 
                    'description' => 'Recibo de pago del impuesto predial actualizado',
                    'description_rec' => '', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 3,
                    'secuencia' => 50,
                    'requerido' => 1, 
                    'condicion_visible' => '',
                     'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                     'status' => 1, 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],

                [
                    'name' => 'recibo_cfe',
                     'type' => 'file', 
                    'description' => 'Recibo CFE no mayor a 3 meses de antigüedad',
                     'description_rec' => '', 
                    'fundamento' => '',
                     'opciones' => '', 
                    'opciones_desc' => '',
                     'step' => 1 ,
                    'secuencia' => 50, 
                    'requerido' => 1, 
                    'condicion_visible' => '',
                     'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                     'status' =>1 , 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],

                [
                    'name' => 'pago_luz',
                     'type' => 'file', 
                    'description' => 'Pago de luz no mayor a 3 meses de antigüedad',
                     'description_rec' => '', 
                    'fundamento' => '',
                     'opciones' => '', 
                    'opciones_desc' => '',
                     'step' => 1,
                    'secuencia' => 50 , 
                    'requerido' => 1, 
                    'condicion_visible' => '',
                     'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                     'status' => 1 , 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],

                [
                    'name'              => 'recibo_agua',
                     'type' => 'file', 
                    'description'       => 'Recibo de pago del agua no mayor a 3 meses de antigüedad',
                    'description_rec'   => '', 
                    'fundamento'        => '',
                    'opciones'          => '', 
                    'opciones_desc'     => '', 
                    'step'              => 3,
                    'secuencia'         => 50 ,
                    'requerido'         => 1 , 
                    'condicion_visible' => '',
                    'campo_afectado'    => '', 
                    'tipo_tramite'      => 'oficial', 
                    'status'            => 1 , 
                    'id_municipio'      =>0
                    ,'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],

                [
                    'name' => 'comprobante_negocio',
                     'type' => 'file', 
                    'description' => 'Comprobante de domicilio vigente del negocio (Agua o luz no mayor a 3 meses de antigüedad)',
                     'description_rec' => '', 
                    'fundamento' => '',
                     'opciones' => '', 
                    'opciones_desc' => '',
                     'step' =>4 ,
                    'secuencia' =>50 , 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                     'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                     'status' => 1, 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],

                [
                    'name' => 'comprobante_titular',
                     'type' => 'file', 
                    'description' => 'Comprobante de domicilio vigente del titular de la licencia (Agua o luz no mayor a 3 meses de antigüedad)',
                     'description_rec' => '', 
                    'fundamento' => '',
                     'opciones' => '', 
                    'opciones_desc' => '',
                     'step' => 1,
                    'secuencia' =>50 , 
                    'requerido' => 1, 
                    'condicion_visible' => '',
                     'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                     'status' => 1, 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],

                [
                    'name' => 'contrato_agua',
                    'type' => 'file', 
                    'description' => 'Contrato del agua',
                    'description_rec' => '', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 3,
                    'secuencia' =>50 ,
                    'requerido' => 1, 
                    'condicion_visible' => '', 
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0
                    ,'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],

                [
                    'name' => 'foto_fuera',
                     'type' => 'multifile', 
                    'description' => '3 Fotografías del inmueble por fuera',
                     'description_rec' => '', 
                    'fundamento' => '',
                     'opciones' => 'Foto 1: fachada| Foto 2: fachada donde se aprecie también la finca o terreno al lado izquierdo| Foto 3: fachada con finca o terreno a lado derecho ', 
                    'opciones_desc' => '',
                    'step' =>4 ,
                    'secuencia' =>50 , 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                     'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                     'status' => 1, 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],
                
                [
                    'name' => 'foto_inmueble',
                     'type' => 'file', 
                    'description' => 'Fotografías del inmueble por dentro del área donde va a operar la actividad solicitada',
                     'description_rec' => '', 
                    'fundamento' => '',
                     'opciones' => '', 
                    'opciones_desc' => '',
                     'step' =>4 ,
                    'secuencia' => 50, 
                    'requerido' => 1, 
                    'condicion_visible' => '',
                     'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                     'status' => 1, 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],

                [
                    'name' => 'alta_hacienda_jalisco',
                     'type' => 'file', 
                    'description' => 'Alta ante la Secretaría de Hacienda del Estado de Jalisco',
                     'description_rec' => '', 
                    'fundamento' => '',
                     'opciones' => '', 
                    'opciones_desc' => '',
                     'step' => 1,
                    'secuencia' => 50, 
                    'requerido' => 1 , 
                    'condicion_visible' => '',
                     'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                     'status' => 1, 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],

                [
                    'name' => 'alta_hacienda_sat',
                     'type' => 'file', 
                    'description' => 'Alta ante el SAT (Servicio de Administración Tributaria)',
                     'description_rec' => '', 
                    'fundamento' => '',
                     'opciones' => '', 
                    'opciones_desc' => '',
                     'step' =>1 ,
                    'secuencia' => 50, 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                     'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                     'status' => 1, 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],

                [
                    'name' => 'anuencia',
                     'type' => 'file', 
                    'description' => 'Anuencia de vecinos / Plaza Comercial', 
                    'description_rec' => '', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '', 
                    'step' =>4 ,
                    'secuencia' =>50 ,
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0
                    ,'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name' => 'CURP',
                    'type' => 'input', 
                    'description' => 'CURP del solicitante',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>40 , 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                    ],
                [
                    'name' => 'licencia_sanitaria',
                     'type' => 'file', 
                    'description' => 'Licencia sanitaria',
                     'description_rec' => '', 
                    'fundamento' => '',
                     'opciones' => '', 
                    'opciones_desc' => '',
                     'step' => 4 ,
                    'secuencia' =>50, 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                     'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                     'status' => 1, 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],

                [
                    'name' => 'documento_propiedad',
                     'type' => 'file', 
                    'description' => 'Documento que acredite la propiedad', 
                    'description_rec' => 'Documento que acredite la propiedad (Escrituras, título de propiedad, cesión de derechos, etc.)', 
                    'fundamento' => '', 
                    'opciones' => '', 
                    'opciones_desc' => '', 
                    'step' => 2 ,
                    'secuencia' =>50, 
                    'requerido' =>2, 
                    'condicion_visible' => '/propietario_rad=="propietario_i" || /propietario_rad=="representante_j"',
                     'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                     'status' => 1, 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],
                [
                    'name' => 'identificacion_apoderado_representante', 
                    'type' => 'file', 
                    'description' => 'Identificación oficial del apoderado',
                    'description_rec' => 'Identificación oficial del apoderado ', 
                    'fundamento' => '',
                     'opciones' => '', 
                    'opciones_desc' => '', 
                    'step' => 1 ,
                    'secuencia' =>50, 
                    'requerido' =>2, 
                    'condicion_visible' => '/propietario_rad=="representante_j"', 
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0
                    ,'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name' => 'acta_constitutiva_moral',
                    'type' => 'file', 
                    'description' => 'Acta constitutiva de la persona moral que es propietaria',
                     'description_rec' => 'Acta constitutiva de la persona moral que es propietaria', 
                    'fundamento' => '', 
                    'opciones' => '', 
                    'opciones_desc' => '',
                     'step' => 2 ,
                    'secuencia' =>50, 
                    'requerido' =>2, 
                    'condicion_visible' => '/propietario_rad=="representante_j"',
                     'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                     'status' => 1, 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],
                [
                    'name' => 'identificacion_arrendatario',
                     'type' => 'file', 
                    'description' => 'Identificación oficial del arrendatario',
                     'description_rec' => 'Identificación oficial del arrendatario', 
                    'fundamento' => '',
                     'opciones' => '', 
                    'opciones_desc' => '',
                     'step' => 1 ,
                    'secuencia' =>50, 
                    'requerido' =>2, 
                    'condicion_visible' => '/arrendatario_rad=="arrendatario_i"',
                     'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                     'status' => 1, 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],
                [
                    'name' => 'identificacion_apoderado_arrendatario',
                    'type' => 'file', 
                    'description' => 'Identificación oficial de apoderado',
                     'description_rec' => 'Identificación oficial de apoderado', 
                    'fundamento' => '',
                     'opciones' => '', 
                    'opciones_desc' => '',
                     'step' => 1 ,
                    'secuencia' =>50, 
                    'requerido' =>2, 
                    'condicion_visible' => '/arrendatario_rad=="representante_arr"',
                     'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                     'status' => 1, 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],
                [
                    'name' => 'acta_constitutiva_moral_arrendatario',
                    'type' => 'file', 
                    'description' => 'Acta constitutiva de la persona moral que es arrendataria',
                    'description_rec' => 'Acta constitutiva de la persona moral que es arrendataria', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>50, 
                    'requerido' =>2, 
                    'condicion_visible' => '/arrendatario_rad=="representante_arr"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0
                    ,'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name'              => 'contrato_arrendamiento',
                    'type'              => 'file', 
                    'description'       => 'Contrato de arrendamiento con cláusula que faculte para abrir un negocio',
                    'description_rec'   => 'Contrato de arrendamiento con cláusula que faculte para abrir un negocio', 
                    'fundamento'        => '',
                    'opciones'          => '', 
                    'opciones_desc'     => '',
                    'step'              => 4 ,
                    'secuencia'         =>50, 
                    'requerido'         =>2, 
                    'condicion_visible' => '/arrendatario_rad=="representante_arr"',
                    'campo_afectado'    => '', 
                    'tipo_tramite'      => 'oficial',
                    'status'            => 1, 
                    'id_municipio'      =>0
                    ,'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name'             => 'identificacion_dueno_negocio',
                    'type'             => 'file', 
                    'description'      => 'Identificación de quien será dueño del negocio',
                    'description_rec'  => 'Identificación de quien será dueño del negocio', 
                    'fundamento'       => '',
                    'opciones'         => '', 
                    'opciones_desc'    => '',
                    'step'             => 1 ,
                    'secuencia'        =>50, 
                    'requerido'        =>2, 
                    'condicion_visible'=> '/carta_poder_rad=="persona_fisica"',
                    'campo_afectado'   => '', 
                    'tipo_tramite'     => 'oficial',
                    'status'           => 1, 
                    'id_municipio'     =>0,
                    'editable'         =>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name'              => 'identificacion_apoderado',
                    'type'              => 'file', 
                    'description'       => 'Identificación del apoderado por carta poder simple ',
                    'description_rec'   => 'Identificación del apoderado por carta poder simple ', 
                    'fundamento'        => '',
                    'opciones'          => '', 
                    'opciones_desc'     => '',
                    'step'              => 1 ,
                    'secuencia'         =>50, 
                    'requerido'         =>2, 
                    'condicion_visible' => '/carta_poder_rad=="persona_fisica"',
                    'campo_afectado'    => '', 
                    'tipo_tramite'      => 'oficial',
                    'status'            => 1, 
                    'id_municipio'      =>0,
                    'editable'          =>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name' => 'carta_poder_simple_testigos',
                    'type' => 'file', 
                    'description' => 'Carta poder simple firmada por dos testigos, acompañada de las identificaciones de los testigos', 
                    'description_rec' => 'Carta poder simple firmada por dos testigos, acompañada de las identificaciones de los testigos', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>50, 
                    'requerido' =>2, 
                    'condicion_visible' => '/carta_poder_rad=="persona_fisica" || /carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name' => 'identificacion_apoderado_persona_moral',
                    'type' => 'file', 
                    'description' => 'Identificación del apoderado de la persona moral que será dueña del negocio', 
                    'description_rec' => 'Identificación del apoderado de la persona moral que será dueña del negocio', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>50, 
                    'requerido' =>2, 
                    'condicion_visible' => '/carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0
                    ,'editable'=>0,
                    'campo_estatico'=>0 ,
                    'requerido_funcionario'=>0,
                    ],
                [
                    'name' => 'acta_contitutiva_persona_moral',
                     'type' => 'file', 
                    'description' => 'Acta constitutiva de la persona moral que será dueña del negocio', 
                    'description_rec' => 'Acta constitutiva de la persona moral que será dueña del negocio', 
                    'fundamento' => '',
                     'opciones' => '', 
                    'opciones_desc' => '',
                     'step' => 1 ,
                    'secuencia' =>50, 
                    'requerido' =>2, 
                    'condicion_visible' => '/carta_poder_rad=="persona_moral"',
                     'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                     'status' => 1, 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],
                [
                    'name' => 'identificacion_apoderado_cata_poder_simple',
                    'type' => 'file', 
                    'description' => 'Identificación del apoderado por carta poder simple ', 
                    'description_rec' => 'Identificación del apoderado por carta poder simple ', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>50, 
                    'requerido' =>2, 
                    'condicion_visible' => '/carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0
                    ,'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name' => 'alineamiento_numero_oficial',
                    'type' => 'file', 
                    'description' => 'Alineamiento y Asignación de Número Oficial', 
                    'description_rec' => 'Alineamiento y Asignación de Número Oficial', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 4 ,
                    'secuencia' =>50, 
                    'requerido' =>1, 
                    'condicion_visible' => '',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0
                    ,'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name' => 'foto_inmueble_estacionamiento',
                     'type' => 'multifile', 
                    'description' => 'Fotografías del inmueble y estacionamiento',
                     'description_rec' => '', 
                    'fundamento' => '',
                     'opciones' => 'Foto 1: fachada| Foto 2: fachada donde se aprecie la finca o terreno al lado izquierdo| Foto 3: fachada con finca o terreno al lado derecho| Foto 4: Interior del inmueble donde se va a establecer el negocio| Foto 5: Estacionamiento destinado al negocio ', 
                    'opciones_desc' => '',
                     'step'    =>4 ,
                    'secuencia' =>50 , 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                     'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                     'status' => 1, 
                     'id_municipio'=>0
                     ,'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                    ],
                [
                    'name' => 'foto_inmueble_2',
                    'type' => 'multifile', 
                    'description' => 'Fotografías del inmueble y estacionamiento',
                    'description_rec' => '', 
                    'fundamento' => '',
                    'opciones' => 'Foto 1: fachada| Foto 2: fachada donde se aprecie la finca o terreno al lado izquierdo| Foto 3: fachada con finca o terreno al lado derecho| Foto 4: Interior del inmueble donde se va a establecer el negocio ', 
                    'opciones_desc' => '',
                    'step' =>1 ,
                    'secuencia' =>50 , 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0
                    ,'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name' => 'formato_tesoreria',
                    'type' => 'file', 
                    'description' => 'Formato en Tesorería',
                    'description_rec' => '', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '', 
                    'step' =>4 ,
                    'secuencia' =>50 , 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0
                    ,'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name'              => 'foto_a_color_inmueble',
                    'type'              => 'file', 
                    'description'       => 'Fotografías a color del inmueble: Una fotografía de la fachada y 2 del interior con puertas abiertas y mucha luz',
                    'description_rec'   => '', 
                    'fundamento'        => '',
                    'opciones'          => '', 
                    'opciones_desc'     => '',
                    'step'              =>4 ,
                    'secuencia'         =>50 , 
                    'requerido'         =>1 , 
                    'condicion_visible' => '',
                    'campo_afectado'    => '', 
                    'tipo_tramite'      => 'oficial',
                    'status'            => 1, 
                    'id_municipio'      => 0
                    ,'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],

                /*
                    Datos del propietario
                */

                [
                    'name' => 'nombre_propietario',
                    'type' => 'input', 
                    'description' => 'Nombre del propietario',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 2 ,
                    'secuencia' =>1, 
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
                [
                    'name' => 'apellido_1_propietario',
                    'type' => 'input', 
                    'description' => 'Primer apellido',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 2 ,
                    'secuencia' =>2, 
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
                [
                    'name' => 'apellido_2_propietario',
                    'type' => 'input', 
                    'description' => 'Segundo apellido',
                    'description_rec' => '', 
                    'fundamento' => '',
                    'opciones' => '|-|', 
                    'opciones_desc' => '',
                    'step' => 2 ,
                    'secuencia' =>3, 
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
                [
                    'name' => 'curp_propietario',
                    'type' => 'input', 
                    'description' => 'CURP del propietario',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 2 ,
                    'secuencia' =>4, 
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
                [
                    'name' => 'rfc_propietario',
                    'type' => 'input', 
                    'description' => 'RFC del propietario',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 2 ,
                    'secuencia' =>5, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/propietario_rad=="propietario_i" || /arrendatario_rad=="arrendatario_i"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name' => 'correo_electronico_propietario',
                    'type' => 'input', 
                    'description' => 'Correo electrónico del propietario',
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
                [
                    'name' => 'domicilio_propietario',
                    'type' => 'input', 
                    'description' => 'Domicilio',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 2 ,
                    'secuencia' =>7, 
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
                [
                    'name' => 'no_exterior_propietario',
                    'type' => 'input', 
                    'description' => 'No. exterior y/o letra',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 2 ,
                    'secuencia' =>10, 
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
                [
                    'name' => 'no_interior_propietario',
                    'type' => 'input', 
                    'description' => 'No. interior y/o letra',
                    'description_rec' => '', 
                    'fundamento' => '',
                    'opciones' => '|-|', 
                    'opciones_desc' => '',
                    'step' => 2 ,
                    'secuencia' =>11, 
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
                [
                    'name' => 'colonia_propietario',
                    'type' => 'input', 
                    'description' => 'Colonia',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 2 ,
                    'secuencia' =>8, 
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
                [
                    'name' => 'cp_propietario',
                    'type' => 'input', 
                    'description' => 'Código postal',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 2 ,
                    'secuencia' =>9, 
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
                
                
                // Fin de propietario
            
            
            
                // Inicio de arrendatario
                [
                    'name' => 'nombre_arrendatario',
                    'type' => 'input', 
                    'description' => 'Nombre del arrendatario',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>1, 
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
                [
                    'name' => 'apellido_1_arrendatario',
                    'type' => 'input', 
                    'description' => 'Primer apellido',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>2, 
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
                [
                    'name' => 'apellido_2_arrendatario',
                    'type' => 'input', 
                    'description' => 'Segundo apellido',
                    'description_rec' => '', 
                    'fundamento' => '',
                    'opciones' => '|-|', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>3, 
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
                [
                    'name' => 'curp_arrendatario',
                    'type' => 'input', 
                    'description' => 'CURP del arrendatario',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>4, 
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
                [
                    'name' => 'rfc_arrendatario',
                    'type' => 'input', 
                    'description' => 'RFC del arrendatario',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '|-|', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>5, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/arrendatario_rad=="arrendatario_i"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name' => 'correo_electronico_arrendatario',
                    'type' => 'input', 
                    'description' => 'Correo electrónico del arrendatario',
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
                [
                    'name' => 'domicilio_arrendatario',
                    'type' => 'input', 
                    'description' => 'Domicilio',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>7, 
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
                [
                    'name' => 'no_exterior_arrendatario',
                    'type' => 'input', 
                    'description' => 'No. exterior y/o letra',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>10, 
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
                [
                    'name' => 'no_interior_arrendatario',
                    'type' => 'input', 
                    'description' => 'No. interior y/o letra',
                    'description_rec' => '', 
                    'fundamento' => '',
                    'opciones' => '|-|', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>11, 
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
                [
                    'name' => 'colonia_arrendatario',
                    'type' => 'input', 
                    'description' => 'Colonia',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>8, 
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
                [
                    'name' => 'cp_arrendatario',
                    'type' => 'input', 
                    'description' => 'Código postal',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>9, 
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
                
                
                // Fin de arrendatario
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
                // Inicio de apoderado
                [
                    'name' => 'nombre_apoderado',
                    'type' => 'input', 
                    'description' => 'Nombre del apoderado',
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
                [
                    'name' => 'apellido_1_apoderado',
                    'type' => 'input', 
                    'description' => 'Primer apellido',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1,
                    'secuencia' =>13, 
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
                [
                    'name' => 'apellido_2_apoderado',
                    'type' => 'input', 
                    'description' => 'Segundo apellido',
                    'description_rec' => '', 
                    'fundamento' => '',
                    'opciones' => '|-|', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>14, 
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
                [
                    'name' => 'curp_apoderado',
                    'type' => 'input', 
                    'description' => 'CURP del apoderado',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>15, 
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
                [
                    'name' => 'rfc_apoderado',
                    'type' => 'input', 
                    'description' => 'RFC del apoderado',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '|-|', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>16, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/carta_poder_rad=="persona_fisica" || /carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name' => 'correo_electronico_apoderado',
                    'type' => 'input', 
                    'description' => 'Correo electrónico del apoderado',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>17, 
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
                [
                    'name' => 'domicilio_apoderado',
                    'type' => 'input', 
                    'description' => 'Domicilio',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>18, 
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
                [
                    'name' => 'no_exterior_apoderado',
                    'type' => 'input', 
                    'description' => 'No. exterior y/o letra',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>21, 
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
                [
                    'name' => 'no_interior_apoderado',
                    'type' => 'input', 
                    'description' => 'No. interior y/o letra',
                    'description_rec' => '', 
                    'fundamento' => '',
                    'opciones' => '|-|', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>22, 
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
                [
                    'name' => 'colonia_apoderado',
                    'type' => 'input', 
                    'description' => 'Colonia',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' =>1 ,
                    'secuencia' =>19, 
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
                [
                    'name' => 'cp_apoderado',
                    'type' => 'input', 
                    'description' => 'Código postal',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>20, 
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
                
                
                // Fin de apoderado
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
                // Inicio persona dueña del negocio
            
                [
                    'name' => 'nombre_dueno_negocio',
                    'type' => 'input', 
                    'description' => 'Nombre del dueño del negocio',
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
                [
                    'name' => 'apellido_1_dueno_negocio',
                    'type' => 'input', 
                    'description' => 'Primer apellido',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1,
                    'secuencia' =>2, 
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
                [
                    'name' => 'apellido_2_dueno_negocio',
                    'type' => 'input', 
                    'description' => 'Segundo apellido',
                    'description_rec' => '', 
                    'fundamento' => '',
                    'opciones' => '|-|', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>3, 
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
                [
                    'name' => 'curp_dueno_negocio',
                    'type' => 'input', 
                    'description' => 'CURP del dueño del negocio',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>4, 
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
                [
                    'name' => 'rfc_dueno_negocio',
                    'type' => 'input', 
                    'description' => 'RFC del dueño del negocio',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '|-|', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>5, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/carta_poder_rad=="persona_fisica"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name' => 'correo_electronico_dueno_negocio',
                    'type' => 'input', 
                    'description' => 'Correo electrónico del dueño del negocio',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>6, 
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
                [
                    'name' => 'domicilio_dueno_negocio',
                    'type' => 'input', 
                    'description' => 'Domicilio',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>7, 
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
                [
                    'name' => 'no_exterior_dueno_negocio',
                    'type' => 'input', 
                    'description' => 'No. exterior y/o letra',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>10, 
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
                [
                    'name' => 'no_interior_dueno_negocio',
                    'type' => 'input', 
                    'description' => 'No. interior y/o letra',
                    'description_rec' => '', 
                    'fundamento' => '',
                    'opciones' => '|-|', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>11, 
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
                [
                    'name' => 'colonia_dueno_negocio',
                    'type' => 'input', 
                    'description' => 'Colonia',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' =>1 ,
                    'secuencia' =>8, 
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
                [
                    'name' => 'cp_dueno_negocio',
                    'type' => 'input', 
                    'description' => 'Código postal',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>9, 
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
                
                
                // Fin de persona dueña del negocio
            
            
                // Inicio persona apoderado moral
            
                [
                    'name' => 'nombre_apoderado_moral',
                    'type' => 'input', 
                    'description' => 'Nombre del apoderado de la empresa',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>12, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/propietario_rad=="representante_j" || /arrendatario_rad=="representante_arr" || /carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'apellido_1_apoderado_moral',
                    'type' => 'input', 
                    'description' => 'Primer apellido',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1,
                    'secuencia' =>13, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/propietario_rad=="representante_j" || /arrendatario_rad=="representante_arr" || /carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'apellido_2_apoderado_moral',
                    'type' => 'input', 
                    'description' => 'Segundo apellido',
                    'description_rec' => '', 
                    'fundamento' => '',
                    'opciones' => '|-|', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>14, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/propietario_rad=="representante_j" || /arrendatario_rad=="representante_arr" || /carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'curp_apoderado_moral',
                    'type' => 'input', 
                    'description' => 'CURP del apoderado moral',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>15, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/propietario_rad=="representante_j" || /arrendatario_rad=="representante_arr" || /carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'rfc_apoderado_moral',
                    'type' => 'input', 
                    'description' => 'RFC del apoderado moral',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '|+|', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>16, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/propietario_rad=="representante_j" || /arrendatario_rad=="representante_arr" || /carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name' => 'correo_electronico_apoderado_moral',
                    'type' => 'input', 
                    'description' => 'Correo electrónico del apoderado moral',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>17, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/propietario_rad=="representante_j" || /arrendatario_rad=="representante_arr" || /carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'domicilio_apoderado_moral',
                    'type' => 'input', 
                    'description' => 'Domicilio',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>20, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/propietario_rad=="representante_j" || /arrendatario_rad=="representante_arr" || /carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'no_exterior_apoderado_moral',
                    'type' => 'input', 
                    'description' => 'No. exterior y/o letra',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>21, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/propietario_rad=="representante_j" || /arrendatario_rad=="representante_arr" || /carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'no_interior_apoderado_moral',
                    'type' => 'input', 
                    'description' => 'No. interior y/o letra',
                    'description_rec' => '', 
                    'fundamento' => '',
                    'opciones' => '|-|', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>22, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/propietario_rad=="representante_j" || /arrendatario_rad=="representante_arr" || /carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'colonia_apoderado_moral',
                    'type' => 'input', 
                    'description' => 'Colonia',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' =>1 ,
                    'secuencia' =>18, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/propietario_rad=="representante_j" || /arrendatario_rad=="representante_arr" || /carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'cp_apoderado_moral',
                    'type' => 'input', 
                    'description' => 'Código postal',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>19, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/propietario_rad=="representante_j" || /arrendatario_rad=="representante_arr" || /carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                
                // Fin de persona apoderado moral
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
                
                
                // Inicio de persona moral
                [
                    'name' => 'razon_social',
                    'type' => 'input', 
                    'description' => 'Razon social',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 2 ,
                    'secuencia' =>1, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/arrendatario_rad=="representante_arr" || /propietario_rad=="representante_j"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'rfc_moral',
                    'type' => 'input', 
                    'description' => 'RFC',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 2,
                    'secuencia' =>2, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/arrendatario_rad=="representante_arr" || /propietario_rad=="representante_j"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name' => 'correo_electronico_moral',
                    'type' => 'input', 
                    'description' => 'Correo electrónico',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 2 ,
                    'secuencia' =>3, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/arrendatario_rad=="representante_arr" || /propietario_rad=="representante_j"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'domicilio_moral',
                    'type' => 'input', 
                    'description' => 'Domicilio',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 2 ,
                    'secuencia' =>6, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/arrendatario_rad=="representante_arr" || /propietario_rad=="representante_j"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'no_exterior_moral',
                    'type' => 'input', 
                    'description' => 'No. exterior y/o letra',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 2 ,
                    'secuencia' =>7, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/arrendatario_rad=="representante_arr" || /propietario_rad=="representante_j"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'no_interior_moral',
                    'type' => 'input', 
                    'description' => 'No. interior y/o letra',
                    'description_rec' => '|-|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 2 ,
                    'secuencia' =>8, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/arrendatario_rad=="representante_arr" || /propietario_rad=="representante_j"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'colonia_moral',
                    'type' => 'input', 
                    'description' => 'Colonia',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' =>2 ,
                    'secuencia' =>4, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/arrendatario_rad=="representante_arr" || /propietario_rad=="representante_j"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'cp_moral',
                    'type' => 'input', 
                    'description' => 'Código postal',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 2 ,
                    'secuencia' =>5, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/arrendatario_rad=="representante_arr" || /propietario_rad=="representante_j"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                
                // fin de persona moral
            
            
            
            
            
            
            
            
            
            
            
            
            
            
                // Inicio de persona dueña del negocio moral
                [
                    'name' => 'razon_social_dueno_moral',
                    'type' => 'input', 
                    'description' => 'Razon social',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>1, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'rfc_moral_dueno_moral',
                    'type' => 'input', 
                    'description' => 'RFC',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1,
                    'secuencia' =>2, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name' => 'correo_electronico_dueno_moral',
                    'type' => 'input', 
                    'description' => 'Correo electronico',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>3, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'domicilio_dueno_moral',
                    'type' => 'input', 
                    'description' => 'Domicilio',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>6, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'no_exterior_dueno_moral',
                    'type' => 'input', 
                    'description' => 'No. exterior y/o letra',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>7, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'no_interior_dueno_moral',
                    'type' => 'input', 
                    'description' => 'No. interior y/o letra',
                    'description_rec' => '|-|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>8, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'colonia_dueno_moral',
                    'type' => 'input', 
                    'description' => 'Colonia',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' =>1 ,
                    'secuencia' =>4, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'cp_dueno_moral',
                    'type' => 'input', 
                    'description' => 'Código postal',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>5, 
                    'requerido' =>2 , 
                    'condicion_visible' => '/carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                

                //Fin dueño moral


                [
                    'name' => 'calle_predio',
                    'type' => 'input', 
                    'description' => 'Calle oficial del predio',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 3 ,
                    'secuencia' =>1, 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'numero_exterior_predio',
                    'type' => 'input', 
                    'description' => 'Número exterior y/o letra',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 3 ,
                    'secuencia' =>2, 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'numero_interior_predio',
                    'type' => 'input', 
                    'description' => 'Número interior y/o letra',
                    'description_rec' => '|-|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 3 ,
                    'secuencia' =>3, 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'colonia_predio',
                    'type' => 'input', 
                    'description' => 'Colonia',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 3 ,
                    'secuencia' =>4, 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'cp_predio',
                    'type' => 'input', 
                    'description' => 'Código postal',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 3 ,
                    'secuencia' =>5, 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],

                /// fin datos predio step 3


                /// inicio step 4
                /*
                    21/06/2021
                    Se agrego un nuevo campo "Local" en el campo de opciones de  la tabla de campos

                */

                [
                    'name' => 'tipo_inmueble',
                    'type' => 'select', 
                    'description' => 'Tipo del inmueble',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => 'Casa|Edificio|Mercado|Plaza|Lote Sin Construir|Local', 
                    'opciones_desc' => '1|2|3|4|5|6',
                    'step' => 4 ,
                    'secuencia' =>1, 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'nombre_negocio',
                    'type' => 'input', 
                    'description' => 'Nombre del negocio',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 4,
                    'secuencia' =>2, 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'numero_empleados',
                    'type' => 'input', 
                    'description' => 'Número de empleados',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 4 ,
                    'secuencia' =>3, 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'inversion_estimada',
                    'type' => 'input', 
                    'description' => 'Inversión estimada',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 4 ,
                    'secuencia' =>4, 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'numero_cajones',
                    'type' => 'input', 
                    'description' => 'No. Cajones de estacionamientos',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 4 ,
                    'secuencia' =>5, 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'descripcion_actividad',
                    'type' => 'input', 
                    'description' => 'Descripción de actividad a realizar',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 4 ,
                    'secuencia' =>6, 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'info_anuncio',
                    'type' => 'input', 
                    'description' => 'Información del anuncio',
                    'description_rec' => '', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 4 ,
                    'secuencia' =>7, 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                ],
                [
                    'name' => 'documento_acredite_disposicion',
                    'type' => 'file', 
                    'description' => 'Documento que acredite la disposición legal del inmueble (Arrendamiento o escritura de propiedad)',
                    'description_rec' => '|+|', 
                    'fundamento' => '',
                    'opciones' => '', 
                    'opciones_desc' => '',
                    'step' => 1 ,
                    'secuencia' =>4, 
                    'requerido' =>7 , 
                    'condicion_visible' => '/carta_poder_rad=="persona_fisica" || /carta_poder_rad=="persona_moral"',
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>0
                    ,'requerido_funcionario'=>0
                ],

                //Fin step 4

                // Campos Estaticos Opcionales
                [
                    'name' => 'anexo_1',
                    'type' => 'file',
                    'description' => 'Otros Archivos', 
                    'description_rec' => '', 
                    'fundamento' => '', 
                    'opciones' => '',
                    'opciones_desc' => '',
                    'step' => 1,
                    'secuencia' => 0, 
                    'requerido' => 0, 
                    'condicion_visible' => '', 
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>1
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name' => 'anexo_2',
                    'type' => 'file',
                    'description' => 'Otros Archivos', 
                    'description_rec' => '', 
                    'fundamento' => '', 
                    'opciones' => '',
                    'opciones_desc' => '',
                    'step' => 2,
                    'secuencia' => 0, 
                    'requerido' => 0, 
                    'condicion_visible' => '', 
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>1
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name' => 'anexo_3',
                    'type' => 'file',
                    'description' => 'Otros Archivos', 
                    'description_rec' => '', 
                    'fundamento' => '', 
                    'opciones' => '',
                    'opciones_desc' => '',
                    'step' => 3,
                    'secuencia' => 0, 
                    'requerido' => 0, 
                    'condicion_visible' => '', 
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>1
                    ,'requerido_funcionario'=>0
                ],
                [
                    'name' => 'anexo_4',
                    'type' => 'file',
                    'description' => 'Otros Archivos', 
                    'description_rec' => '', 
                    'fundamento' => '', 
                    'opciones' => '',
                    'opciones_desc' => '',
                    'step' => 4,
                    'secuencia' => 0, 
                    'requerido' => 0, 
                    'condicion_visible' => '', 
                    'campo_afectado' => '', 
                    'tipo_tramite' => 'oficial',
                    'status' => 1, 
                    'id_municipio'=>0,
                    'editable'=>0,
                    'campo_estatico'=>1
                    ,'requerido_funcionario'=>0
                ],



            ]
        );
        /*
        Example
        [
                    'name' => 'licencia_sanitaria',
                     'type' => 'file', 
                    'description' => 'Licencia sanitaria',
                     'description_rec' => '', 
                    'fundamento' => '',
                     'opciones' => '', 
                    'opciones_desc' => '',
                     'step' => 1 ,
                    'secuencia' =>20, 
                    'requerido' =>1 , 
                    'condicion_visible' => '',
                     'campo_afectado' => '', 
                    'tipo_tramite' => '',
                     'status' => 1, 
                     'id_municipio'=>0,
                     'editable'=>0,
                     'campo_estatico'=>0
                    ,'requerido_funcionario'=>1
                    ],*/
        
    }
}
