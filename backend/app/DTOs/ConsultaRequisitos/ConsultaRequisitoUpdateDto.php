<?php

namespace App\DTOs\ConsultaRequisitos;

use phpDocumentor\Reflection\Types\Float_;
use Spatie\DataTransferObject\DataTransferObject;

class ConsultaRequisitoUpdateDto extends DataTransferObject
{
    public int $id;
    public int $id_usuario;
  
}