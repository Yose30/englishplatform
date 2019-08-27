<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if(auth()->user()->role_id != $role){
            // abort(401, __("No puedes acceder a este sitio"));
            auth()->logout();
            session()->flush();
            return redirect('/login')->with('message', ['danger', __("No puedes acceder a este sitio")]);
        }
        return $next($request);
    }
}
