<?php

namespace App\DTOs\GirosApagados;

use Spatie\DataTransferObject\DataTransferObject;

class GirosApagadosCreateDto extends DataTransferObject
{
    public int $giros_id;
    public int $municipios_id;
   
}
