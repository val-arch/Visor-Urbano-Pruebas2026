<?php

namespace App\DTOs\Usuarios;

use Spatie\DataTransferObject\DataTransferObject;

class UsuarioUpdateDto extends DataTransferObject
{
    public int $id;
    public string  $name ;
    public string $apellido_p; 
    public string $apellido_m; 
    public string $celular; 
    public string $email; 
    public string $password; 
    public string $rfc;  
    public string $curp;   
 
 
}
