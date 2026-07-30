<?php

namespace App\DTOs\Municipios;

use Spatie\DataTransferObject\DataTransferObject;

class MunicipioUpdateDto extends DataTransferObject
{
    public int $id;
    public string $director;
    public int $ficha_tramite;
    public int $dias_solventar;
    public string $direccion;
    public string $telefono;
    public int $licencias_enlinea;

    public ?int $folio_init                      = null;
    public  $precio_lic;
    public  $generar_licencia_ventanilla;
    public string $area_encargada;
    public string $restricciones_licencia;
    public string $url_municipio;
    public ?string $correo_dependencia;
    public ?string $color_hex;
}
