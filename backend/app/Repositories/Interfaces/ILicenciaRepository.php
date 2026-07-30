<?php
namespace App\Repositories\Interfaces;
use App\DTOs\Licencia\LicenciaUpdateDto;

interface ILicenciaRepository {   
    public function update(LicenciaUpdateDto $store): void;   
}