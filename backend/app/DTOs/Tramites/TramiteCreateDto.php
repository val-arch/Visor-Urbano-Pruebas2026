<?php

namespace App\DTOs\Tramites;

use Spatie\DataTransferObject\DataTransferObject;

class TramiteCreateDto extends DataTransferObject
{
    public string $sku;
    public string $name;
    public string $description;
    public float $price;
}
