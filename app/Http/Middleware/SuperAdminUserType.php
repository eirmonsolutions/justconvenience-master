<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class SuperAdminUserType
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
        if(!Auth::check())
        {
            return redirect()->route('signin');
        }

        if(Auth::check() && (Auth::user()->user_role == 2 || Auth::user()->user_role == 3))
        {
            return redirect()->route('dashboard');
        }

        if(Auth::check() && (Auth::user()->user_role == 4))
        {
            return redirect()->route('customer-profile');
        }
        return $next($request);
    }
}
