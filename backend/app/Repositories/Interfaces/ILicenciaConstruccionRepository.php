<?php
namespace App\Repositories\Interfaces;
use App\DTOs\Licencia\LicenciaUpdateDto;

interface ILicenciaConstruccionRepository {   
    public function update(LicenciaUpdateDto $store): void;   
}