<?php

namespace App;

use App\Models\Role;
use App\Models\RoleConstruccion;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function roles_construccion()
    {
        return $this->belongsToMany(RoleConstruccion::class, 'user_roles_construccion', 'user_id', 'role_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','apellido_m', 'apellido_p','subrole_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'api_token', 'api_token_expiration',
    ];
}
