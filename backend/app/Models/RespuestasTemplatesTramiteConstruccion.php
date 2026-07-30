<?php   
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RespuestasTemplatesTramiteConstruccion extends Model{
   use SoftDeletes;
   protected $table = "respuestas_templates_tramites_construccion"; 
   protected $fillable = ['id_tramite'];
}