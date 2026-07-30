<?php

namespace App\DTOs\CamposRolesConstruccion;

use Spatie\DataTransferObject\DataTransferObject;

class CampoUpdateDto extends DataTransferObject
{
    public int $id;
    public string $type;   
    public string $description;   
    public string $description_rec;   
    public string $fundamento;   
    public string $opciones;   
    public string $opciones_desc;   
    public int $step;   
    public int $secuencia;   
    public int $requerido;   
    public string $condicion_visible;   
    public string $condicion_dependencia;   
    public string $condicion_giro;   
    public string $campo_afectado;   
    public string $tipo_tramite;
    public int $dependencias;
    public int $tramite_relacionado;
    public $info_licencia;

    public string $actividad_condicion;
    public int $actividad_metros;
    public string $construccion_total_metros_condicion;
    public int $construccion_total_metros_value;
    public array $construccion_uso;
    public array $construccion_uso_destino;
    public array $construccion_uso_metros;
    public string $demolicion_metros_condicion;
    public int $demolicion_metros_value;
    public string $nivel_nuevo_construccion_condicion;
    public int $nivel_nuevo_construccion_value;
    public string $sotano_nuevo_construccion_condicion;
    public int $sotano_nuevo_construccion_value;
    public string $todasCondiciones;
    public string $viviendas_construccion_condicion;
    public int $viviendas_construccion_value;

    public string $pregunta_si_o_no_condicion;
    public int $pregunta_si_o_no_value;
    public $id_municipio;   
   
}
