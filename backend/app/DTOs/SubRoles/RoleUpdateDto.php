<?php

namespace App\DTOs\SubRoles;

use Spatie\DataTransferObject\DataTransferObject;

class SubRoleUpdateDto extends DataTransferObject
{
    public int $id;
    public string $nombre;
    public string $descripcion;
 
}
