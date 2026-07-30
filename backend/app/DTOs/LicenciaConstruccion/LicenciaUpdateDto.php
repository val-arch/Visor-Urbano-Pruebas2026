<?php

namespace App\DTOs\LicenciaConstruccion;

use Spatie\DataTransferObject\DataTransferObject;

class LicenciaUpdateDto extends DataTransferObject
{
    public int $id;
    public string $tipo_licencia;
    public string $status_licencia;
    public string $motivo;
}
