<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, string $role, $permission = null)
    {
        
        $isValid = Auth::user()->roles()->where('name', $role)->exists();

        if($role != null) {
           $roles = explode("|", $role);
           foreach ($roles as $key => $value) {
               
            $isValid[] = Auth::user()->roles()->where('name', $value)->exists();
           }

        }
        if (in_array(true, $isValid)) {
            return $next($request);
        }else{
            return response('Forbidden.', 403);
        }

    }
}
