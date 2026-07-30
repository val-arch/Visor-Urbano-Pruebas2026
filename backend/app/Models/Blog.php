<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model{
    protected $table = "blog";
    protected $fillable = ['titulo',
    'img',
    'link',
    'fecha_noticia',
    'type',
    'body',
    'publicado'];
    public $timestamps = false;
}