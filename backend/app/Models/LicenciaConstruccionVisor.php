<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LicenciaConstruccionVisor extends Model
{
    protected $table = "licencias_construccion_visor";
    protected $fillable = ['folio',
    'actividad_comercial',
    'apellido_p',
    'apellido_m',
    'curp',
    'img_logo',
    'firma',
    'url_minimapa',
    'anio_licencia',
    'id_usuario_genero','hora_a',
    'hora_c',
    'pdf_escaneado',
    'municipio_id',
    'numero_lic',
    'tipo_licencia',
    'tipo_construccion',
    'firmantes',
    'status_licencia',
    'observaciones',
    'dueno',
    'consecutivo',
    'urlLicenciaPdf'
    ];

    protected $casts = [
        'nombres_archivos_prorroga' => 'array',
        'rutas_archivos_prorroga' => 'array',
        'firmantes' => 'array'
    ];
}


