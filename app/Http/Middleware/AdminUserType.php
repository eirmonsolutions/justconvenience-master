<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminUserType
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
        if(Auth::user()->user_role != 2)
        {
            return redirect()->route('stores');
        }

        return $next($request);
    }
}
