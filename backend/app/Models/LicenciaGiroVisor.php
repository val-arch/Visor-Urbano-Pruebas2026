<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LicenciaGiroVisor extends Model
{
    protected $table = "licencias_giro_visor";
    protected $fillable = ['folio',
    'actividad_comercial',
    'codigo_scian',
    'superficie_autorizada',
    'dueno',
    'apellido_p',
    'apellido_m',
    'caracter',
    'curp',
    'img_logo',
    'firma',
    'url_minimapa',
    'anio_licencia',
    'id_usuario_genero','hora_a',
    'hora_c',
    'nombre_firmante_1',
    'dependencia_1',
    'firma_1',
    'nombre_firmante_2',
    'dependencia_2',
    'firma_2',
    'nombre_firmante_3',
    'dependencia_3',
    'firma_3',
    'nombre_firmante_4',
    'dependencia_4',
    'firma_4',
    'pdf_escaneado',
    'municipio_id',
    'numero_lic',
    'tipo_licencia',
    'status_licencia',
    'observaciones',
    'costo_licencia',
    'firmantes',
    'color_licencia'
];
}


