<?php

namespace App\DTOs\ConsultaRequisitosConstruccion;

use phpDocumentor\Reflection\Types\Float_;
use Spatie\DataTransferObject\DataTransferObject;

class ConsultaRequisitoConstruccionUpdateDto2 extends DataTransferObject
{
    public Float $superficie_habitacional;
    public Float $superficie_comercial_servicios;
    public Float $superficie_industrial;
    public Float $superficie_turistico;
    public Float $superficie_equipamiento;
    public Float $superficie_espacios_verdes;
    public Float $superficie_otro;
    public $mdemolicion;
  
}