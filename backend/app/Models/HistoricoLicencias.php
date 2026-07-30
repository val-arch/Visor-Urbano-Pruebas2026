<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class HistoricoLicencias extends Model{
    use SoftDeletes;
    protected $table = "historico_licencias_giro"; 

    protected $fillable = [
            'folio_licencia', 
            'fecha_emision',
            'giro',
            'descripcion_detallada',
            'codigo_giro',
            'superficie_giro',
            'calle',
            'numero_ext',
            'numero_int',
            'colonia',
            'clave_catastral',
            'referencia',
            'coordonadas_x',
            'coordonadas_y',
            'nombre_titular', 
            'apellido_p',
            'apellido_m',
            'rfc' ,
            'curp' ,
            'telefono' ,
            'razon_social',
            'email' ,
            'calle_titular',
            'numero_ext_titular',
            'numero_int_titular',
            'colonia_titular',
            'venta_alcohol',
            'horario',
            'id_municipio',
            'status',
            'status_pago',
            'status_licencia',
            'calle_predio',
            'colonia_predio',
            'num_int_predio',
            'num_ext_predio',
            'tipo_licencia',
            'anuncio'
        ];
}