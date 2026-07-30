<?php

namespace App\DTOs\Licencia;

use Spatie\DataTransferObject\DataTransferObject;

class LicenciaUpdateDto extends DataTransferObject
{
    public int $id;
    public string $tipo_licencia;
    public string $status_licencia;
    public string $motivo;
}
