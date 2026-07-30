<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CedulaGiro extends Model
{
    protected $table = "giros_cedula";

    public function giros(){
        return $this->hasOne(Giro::class);
    }
}
