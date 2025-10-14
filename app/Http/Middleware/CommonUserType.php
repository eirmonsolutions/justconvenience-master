<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CommonUserType
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

        if(Auth::user()->user_role == 1)
        {
            return redirect()->route('users');
        }

        if(Auth::user()->user_role == 4)
        {
            return redirect()->route('customer-profile');
        }
        return $next($request);
    }
}
