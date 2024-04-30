<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Session;
use Config;
use DB;

class LanguageSwitcher
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
        $locale1 = Session::get('locale');
        $locale2 = Config::get('app.locale');

        if (!empty($locale1)) {
            $locale = $locale1;
        } else {
            $locale = $locale2;
        }

        App::setLocale($locale);
        return $next($request);
    }
}

 