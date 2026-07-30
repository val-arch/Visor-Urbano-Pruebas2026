<?php

namespace App\DTOs\Usuarios;

use Spatie\DataTransferObject\DataTransferObject;

class UsuarioMunicipioUpdateDto extends DataTransferObject
{
    public int $id;
    public int $id_municipio ;

}
