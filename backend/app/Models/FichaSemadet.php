<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FichaSemadet extends Model{
    
    protected $table = "ficha_semadet";
   
    protected $fillable = ['img', 'uuid', 'clave', 'clave_1', 'id_uga', 'coordenadas'];

}