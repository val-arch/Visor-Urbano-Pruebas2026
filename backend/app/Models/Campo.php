<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campo extends Model
{
    use SoftDeletes;

    public function requisitos(){
        return $this->hasOne(Requisito::class);
    }
}
