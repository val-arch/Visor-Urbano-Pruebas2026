<?php

namespace App\DTOs\RequisitosConstruccion;
use Spatie\DataTransferObject\DataTransferObject;
class RequisitoUpdateDto extends DataTransferObject
{
    public int $id;
    public int $municipios_id;
    public string $campos_id;   
    public string $id_requisitos;   
}
