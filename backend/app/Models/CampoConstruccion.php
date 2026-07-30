<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampoConstruccion extends Model
{
    use SoftDeletes;
    protected $table = "campos_construccion";
    public function requisitos(){
        return $this->hasOne(RequisitoConstruccion::class);
    }

    protected $casts = [
        'construccion_uso' => 'array',
        'construccion_uso_destino' => 'array',
        'construccion_uso_metros' => 'array',
    ];
}
