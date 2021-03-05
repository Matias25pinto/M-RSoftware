<?php

namespace sisMR\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class verificarPermiso
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {       
            //func_get_args() carga en un array todo lo enviado al middleware
            //array_slice() permite ignorar elementos de un array
            $roles = array_slice(func_get_args(), 2);
            foreach ($roles as $role) {
                if ( Auth::user()->hasRoles($role) ) {
                    return $next($request);
                } 
            }
            
        return response('No puedes continuar por falta de permisos de usuario.', 401);
    }
}
