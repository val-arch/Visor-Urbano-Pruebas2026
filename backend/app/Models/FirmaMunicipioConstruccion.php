<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FirmaMunicipioConstruccion extends Model{
    use SoftDeletes;
    protected $table = "firmas_municipios_construccion";
    // protected $fillable = [];
    // public $timestamps = false;
}