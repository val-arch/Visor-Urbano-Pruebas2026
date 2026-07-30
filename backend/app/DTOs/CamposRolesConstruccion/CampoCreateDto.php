<?php

namespace App\DTOs\CamposRolesConstruccion;

use Spatie\DataTransferObject\DataTransferObject;

class CampoCreateDto extends DataTransferObject
{
    public string $name;   
    public string $type;   
    public string $description;   
    public string $description_rec;   
    public string $fundamento;   
    public string $opciones;   
    public string $opciones_desc;   
    public $step;   
    public int $secuencia;   
    public $requerido;   
    public string $condicion_visible;   
    public string $condicion_dependencia;   
    public string $condicion_giro;   
    public string $campo_afectado;   
    public $tipo_tramite;   
    public int $status;   
    public int $id_municipio;
    public string $actividad_condicion;
    public int $actividad_metros;
    public $dependencias;
    public $info_licencia;

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
    public int $tramite_relacionado;
}
