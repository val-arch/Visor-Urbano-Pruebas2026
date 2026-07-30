<?php

namespace App\DTOs\ConsultaRequisitosConstruccion;

use phpDocumentor\Reflection\Types\Float_;
use Spatie\DataTransferObject\DataTransferObject;

class ConsultaRequisitoConstruccionUpdateDto extends DataTransferObject
{
    public int $id;
    public int $id_usuario;
  
}