<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subrole extends Model
{
    use SoftDeletes;
    protected $table = 'subroles'; 

    public function users(){

        $this->belongTo(Usuario::class);
    
    }
}

