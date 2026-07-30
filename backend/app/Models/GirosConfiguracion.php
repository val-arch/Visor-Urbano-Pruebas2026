<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GirosConfiguracion extends Model
{
    protected $table = "giros_configuracion";
    protected $fillable = [
        'giro_impacto','giro_apagado','giro_impacto','giros_id','municipios_id'
    ];
}
