<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Routing\UrlGenerator;
use App\Models\Localization;
use Closure;
use Request;
use Route;
use App;
class setLocale
{
    private $url;

    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    public function handle($request, Closure $next)
    {
        if($request->locale == null){
            Route::redirect('home','en');
        }
        if(Localization::where('language_code',$request->locale)->exists()){
            \App::setLocale($request->locale);
        }else{
            Route::redirect('/','en');
        }

        $this->url->defaults([
            'locale' => $request->locale,
        ]);
        return $next($request);
    }
}
