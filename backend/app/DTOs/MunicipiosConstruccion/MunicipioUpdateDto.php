<?php

namespace App\DTOs\MunicipiosConstruccion;

use Spatie\DataTransferObject\DataTransferObject;

class MunicipioUpdateDto extends DataTransferObject
{
    public int $id;
    public string $director;
    public int $ficha_tramite;
    public $dias_solventar;
    public string $direccion;
    public string $telefono;
    public int $licencias_enlinea;
    public  $generar_licencia_ventanilla;
    public string $area_encargada;
    public string $restricciones_licencia;
    public $usuarios_emision;
    public string $correo_dependencia;
}
