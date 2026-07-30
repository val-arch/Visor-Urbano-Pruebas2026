<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FichaTecnicaDescarga extends Model
{
    protected $table = "ficha_tecnica_descargas";


    protected $fillable = ['ciudad',
                            'correo',
                            'edad',
                            'nombre',
                            'sector',
                            'usos'];
}
