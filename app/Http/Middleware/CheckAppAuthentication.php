<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request; 
use App\Models\oauthClient;

class CheckAppAuthentication
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
        if($request->header('apiToken'))
        {
            $checkTokenExistance = OauthClient::with('user')->where(['api_token' => $request->header('apiToken')])->first();
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

        return response()->json(['status' => 0, 'message' => 'Unauthorized Request' ])->setStatusCode(401);
    }
}