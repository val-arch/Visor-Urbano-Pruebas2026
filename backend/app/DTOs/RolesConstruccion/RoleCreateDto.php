<?php

namespace App\DTOs\RolesConstruccion;

use Spatie\DataTransferObject\DataTransferObject;

class RoleCreateDto extends DataTransferObject
{
    public string $name; 
    public string $descripcion;   
    public int $id_municipio; 
}
