<?php

namespace App\DTOs\Historico;

use Spatie\DataTransferObject\DataTransferObject;

class HistoricoUpdateDto extends DataTransferObject
{
   public int $id;
   public ?string $folio_licencia;
   public ?string $fecha_emision;
   public ?string $giro;
   public ?string $descripcion_detallada;
   public ?string $codigo_giro;
   public ?string $superficie_giro;
   public ?string $calle;
   public ?string $numero_ext;
   public ?string $numero_int;
   public ?string $colonia;
   public ?string $clave_catastral;
   public ?string $referencia;
   public ?string $coordonadas_x;
   public ?string $coordonadas_y;
   public ?string $nombre_titular;
   public ?string $apellido_p;
   public ?string $apellido_m;
   public ?string $rfc;
   public ?string $curp; 
   public ?string $telefono;
   public ?string $razon_social;
   public ?string $email; 
   public ?string $calle_titular;
   public ?string $numero_ext_titular;
   public ?string $numero_int_titular;
   public ?string $colonia_titular; 
   public ?string $venta_alcohol;
   public ?string $horario; 
    
 
}
