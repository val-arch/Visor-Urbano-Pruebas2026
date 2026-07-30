<?php   
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplatesTramiteConstruccion extends Model{
   use SoftDeletes;
   protected $table = "templates_tramites_construccion"; 
}