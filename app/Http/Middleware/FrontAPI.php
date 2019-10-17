<?php

namespace App\Http\Middleware;
use Closure;

class FrontAPI
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
        $error = 'Access Denied';
        
        if(!$request->header('authorization'))
            return response()->json($error, 422);
        
        $api_key = $request->header('authorization');        
                
        if(!$api_key || $api_key != config('app.api_key', '') )
            return response()->json($error, 422);
                        
        return $next($request);
        
    }
}
