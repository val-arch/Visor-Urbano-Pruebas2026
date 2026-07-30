<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MunicipioConstruccion extends Model
{
    use SoftDeletes;
    protected $table = "municipios_construccion";

     protected $casts = [
        'usuarios_emision' => 'array'
    ];
}
