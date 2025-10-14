<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CustomerUserType
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
            return redirect()->route('customer-login');
        }

        if(Auth::user()->user_role == 1)
        {
            return redirect()->route('users');
        }

        if(Auth::user()->user_role == 2 || Auth::user()->user_role == 3)
        {
            return redirect()->route('dashboard');
        }

        if(Auth::user()->user_role == 4 && Auth::user()->status != 1)
        {
            return redirect()->route('customer-logout');
        }
        return $next($request);
    }
}
