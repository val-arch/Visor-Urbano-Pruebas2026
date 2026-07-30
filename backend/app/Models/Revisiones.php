<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Revisiones extends Model{
    // use SoftDeletes;
    protected $table = "revisiones_dependencias";

     protected $fillable = ['id_tramite','folio' ,'id_municipio',
     'rol',
     'fecha_inicio',
     'fecha_actualizacion','id_usuario','status_actual'];
    // public $timestamps = false;
    
}