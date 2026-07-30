<?php

namespace App\DTOs\RequisitosConstruccion;

use Spatie\DataTransferObject\DataTransferObject;

class RequisitoCreateDto extends DataTransferObject
{
    public int $municipios_id;
    public string $campos_id;   
    public string $id_requisitos;

}