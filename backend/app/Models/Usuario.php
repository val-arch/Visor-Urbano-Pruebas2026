<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Model
{
    use SoftDeletes;
    protected $table = 'users'; 

    
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function roles_construccion()
    {
        return $this->belongsToMany(RoleConstruccion::class, 'user_roles_construccion', 'user_id', 'role_id');
    }

    public function rolesPendiente_construccion()
    {
        return $this->belongsToMany(RoleConstruccion::class, 'user_roles_construccion', 'user_id', 'role_id_pendiente');
    }

    public function rolesPendiente()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id_pendiente');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','apellido_m', 'apellido_p', 'user_type',
    ];

    protected $hidden = [
        'password', 'api_token', 'api_token_expiration',
    ];


}

