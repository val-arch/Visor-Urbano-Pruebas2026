<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FichaTecnica extends Model
{
    protected $table = "ficha_tecnica";
    protected $fillable = ['uuid',
                            'direccion',
                            'metros',
                            'coordenadas',
                            'img',
                            'municipio_id','id_ficha_tecnica_descarga'];
}
