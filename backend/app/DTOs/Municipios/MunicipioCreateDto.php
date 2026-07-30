<?php

namespace App\DTOs\Municipios;

use Spatie\DataTransferObject\DataTransferObject;

class MunicipioCreateDto extends DataTransferObject
{

    public string $director;
    public string $direccion;
    public string $telefono;
    public string $url_municipio;
    public string $correo_dependencia;
    public string $color_hex;
}
