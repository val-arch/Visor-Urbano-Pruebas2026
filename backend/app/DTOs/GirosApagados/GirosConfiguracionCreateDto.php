<?php

namespace App\DTOs\GirosApagados;

use Spatie\DataTransferObject\DataTransferObject;

class GirosConfiguracionCreateDto extends DataTransferObject
{
    public int $giros_id;
    public int $municipios_id;
    public int $giro_apagado;
   
}
