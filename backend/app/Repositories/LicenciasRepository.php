<?php
namespace App\Repositories;

use App\DTOs\Licencia\LicenciaUpdateDto;
use App\Models\LicenciaGiroVisor;
use App\Repositories\Interfaces\ILicenciaRepository;

class LicenciasRepository implements ILicenciaRepository
{
    public function update(LicenciaUpdateDto $store): void
    {
        $entry                   = LicenciaGiroVisor::find($store->id);
        $entry->status_licencia  = $store->status_licencia;
        $entry->tipo_licencia    = $store->tipo_licencia;
        $entry->motivo           = $store->motivo;
        $entry->save();
    }
}
