<?php

namespace App\DTOs\SubRoles;

use Spatie\DataTransferObject\DataTransferObject;

class SubRoleCreateDto extends DataTransferObject
{
    public string $nombre;   
    public string $descripcion;   
    public int $id_municipio;   
}
