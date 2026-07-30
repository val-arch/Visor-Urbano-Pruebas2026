<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResolucionConstruccion extends Model{
    use SoftDeletes;
    protected $table = "orden_resoluciones_construccion";

    // protected $fillable = [];

    // public $timestamps = false;
}