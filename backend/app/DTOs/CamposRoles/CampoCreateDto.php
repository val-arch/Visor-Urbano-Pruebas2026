<?php

namespace App\DTOs\CamposRoles;

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
    public int $step;   
    public int $secuencia;   
    public int $requerido;   
    public string $condicion_visible;   
    public string $condicion_dependencia;   
    public string $condicion_giro;   
    public string $campo_afectado;   
    public string $tipo_tramite;   
    public int $status;   
    public int $id_municipio;   

}
