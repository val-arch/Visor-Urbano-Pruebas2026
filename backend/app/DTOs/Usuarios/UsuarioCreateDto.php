<?php

namespace App\DTOs\Usuarios;

use Spatie\DataTransferObject\DataTransferObject;

class UsuarioCreateDto extends DataTransferObject
{
    public string $name; 
    public string $apellido_p; 
    public string $apellido_m; 
    public string $celular; 
    public string $email; 
    public string $password; 
    public string $rfc;  
    public string $curp;   
    public int $role;
}
