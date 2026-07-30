<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Giro extends Model
{
    use SoftDeletes;

    public function girosApagados(){
        return $this->hasOne(GiroApagado::class,'');
    }
}
