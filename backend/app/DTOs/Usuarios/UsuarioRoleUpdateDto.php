<?php

namespace App\DTOs\Usuarios;

use Spatie\DataTransferObject\DataTransferObject;

class UsuarioRoleUpdateDto extends DataTransferObject
{
  
    public int $id;
    public int $role_id;
  

}
