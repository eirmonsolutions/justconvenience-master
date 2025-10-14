<?php

namespace App\Http\Middleware;

use Closure;
use App\OauthClient;

class CheckAppAuthentication
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
        if($request->api_token)
        {
            $checkTokenExistance = OauthClient::with('user')->where(['api_token' => $request->api_token])->first();
            if($checkTokenExistance)
            {
                $request->merge(array("checkTokenExistance" => $checkTokenExistance));
                return $next($request);
            }
            else
            {
                return response()->json(['status' => 0, 'message' => 'Invalid Token.' ])->setStatusCode(200);
            }
        }

        return response()->json(['status' => 0, 'message' => 'Unauthorized Request' ])->setStatusCode(200);
    }
}