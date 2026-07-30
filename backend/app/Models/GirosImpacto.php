<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GirosImpacto extends Model
{
    protected $table = "giros_impacto";

    public function giros(){
        return $this->hasOne(Giro::class);
    }
}
