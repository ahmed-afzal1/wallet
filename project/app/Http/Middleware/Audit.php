<?php

namespace App\Http\Middleware;
use App\Helpers\Autoload;
use Closure;

class Audit
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
        Autoload::airlog("Accessing [" . __FUNCTION__ . "]");
        return $next($request);
    }
    
    public function terminate($request, $response)
    {
        // Autoload::airlog("Accessing [" . __FUNCTION__ . "]");
    }

}
