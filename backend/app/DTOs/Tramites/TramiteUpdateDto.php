<?php

namespace App\DTOs\Tramites;

use Spatie\DataTransferObject\DataTransferObject;

class TramiteUpdateDto extends DataTransferObject
{
    public int $id;
    public string $sku;
    public string $name;
    public string $description;
    public float $price;
}
