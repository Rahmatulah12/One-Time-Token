<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OneTimeTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(is_null($request->get('token')) || !$request->get('token'))
        {
            abort(401);
        }
        
        return $next($request);
    }
}
