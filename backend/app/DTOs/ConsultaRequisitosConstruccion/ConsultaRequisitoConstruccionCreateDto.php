<?php

namespace App\DTOs\ConsultaRequisitosConstruccion;

use phpDocumentor\Reflection\Types\Float_;
use Spatie\DataTransferObject\DataTransferObject;

class ConsultaRequisitoConstruccionCreateDto extends DataTransferObject
{

    public string $calleC;
    public string $coloniaC;
    public int $tramite_relacionado;
    public string $municipio;
    public string $localidadC;
    public ?string $coords;
    public ?string $uuid;
    // public string $actividad;
    public int $id_municipio;
    public Float $superficie_propiedad;
    public ?int $mdemolicion;
    public ?int $niveles_nuevos_construir;
    public ?int $numero_viviendas;
    public ?int $sotano;
    // public Float $superficie;
    public string $nombreC;
    //public string $caracter;
    // public string $alcohol;
    // public string $tipo_persona;
    public string $url_minimapa;
    public array $restricciones;
    public Float $superficie_habitacional;
    public Float $superficie_comercial_servicios;
    public Float $superficie_industrial;
    public Float $superficie_turistico;
    public Float $superficie_equipamiento;
    public Float $superficie_espacios_verdes;
    public Float $superficie_otro;
    public string $concepto_otro;
    public array $camposDinamicos;
}
