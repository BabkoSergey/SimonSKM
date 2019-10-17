<?php

namespace App\Http\Middleware;

use Closure;
use App;

class LocaleMiddleware
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
        $locale = $request->segment(1);
        
        if ( !in_array($locale, config('app.locale_enabled', [config('app.locale', 'en')]))) {
            $segments = $request->segments();
            array_unshift($segments,config('app.locale_def', 'en'));

            return redirect(implode('/', $segments));
        }

        App::setLocale($locale);
        
        return $next($request);

    }
}
