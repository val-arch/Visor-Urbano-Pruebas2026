<?php

namespace App\DTOs\GirosApagados;

use Spatie\DataTransferObject\DataTransferObject;

class GirosApagadosUpdateDto extends DataTransferObject
{
    public int $id;
    public int $giros_id;
    public int $municipios_id;
}
