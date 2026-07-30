<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleConstruccion extends Model
{
    use SoftDeletes;
     protected $table = 'roles_construccion'; 

    
    public function users(){
        return $this->belongsToMany(Usuario::class,'user_roles_construccion', 'user_id', 'role_id');
    }
}

