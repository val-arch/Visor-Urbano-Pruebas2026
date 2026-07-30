<?php

namespace App\DTOs\MunicipiosConstruccion;

use Spatie\DataTransferObject\DataTransferObject;

class MunicipioCreateDto extends DataTransferObject
{
    
    public string $director;
    public string $direccion;
    public string $telefono;
    public string $correo_dependencia;
   
}
