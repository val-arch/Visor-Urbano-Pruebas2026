<?php

namespace App\DTOs\RolesConstruccion;

use Spatie\DataTransferObject\DataTransferObject;

class RoleUpdateDto extends DataTransferObject
{
    public int $id;
    public string $name;
    public string $descripcion;
 
}
