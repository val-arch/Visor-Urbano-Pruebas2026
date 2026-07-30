<?php   
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoTramiteConstruccion extends Model{
   use SoftDeletes;
   protected $table = "tipo_tramites_construccion"; 
}