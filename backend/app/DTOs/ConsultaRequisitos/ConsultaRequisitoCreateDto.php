<?php

namespace App\DTOs\ConsultaRequisitos;

use phpDocumentor\Reflection\Types\Float_;
use Spatie\DataTransferObject\DataTransferObject;

class ConsultaRequisitoCreateDto extends DataTransferObject
{

    public string $calle;
    public string $colonia; 
    public string $municipio; 
    public string $localidad;
    public string $actividad;
    public int $id_municipio;  
    public string $codigo_scian;  
    public string $nombre_scian;  
    public Float $superficie_propiedad;
    public Float $superficie;  
    public string $nombre;
    public string $caracter;
    public string $alcohol;
    public string $tipo_persona;
    public string $url_minimapa;
    public string $url_minimapa2;
    public array $restricciones;
    public array $camposDinamicos;
  
}