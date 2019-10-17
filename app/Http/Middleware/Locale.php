<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Cookie;

class Locale 
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
                
        if ( Cookie::has('setLang') ) {            
            App::setLocale(Cookie::get('setLang'));
        }
                
        return $next($request);
    }

}
