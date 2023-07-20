<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AllowGuestAccess
{
    public function handle($request, Closure $next)
    {
        if (auth()->guard('web')->guest()) {
            return $next($request);
        }

        return redirect('/');
    }
}

class Roles
{ 
    public function handle($request, Closure $next, $roles)
    {
        $roles = array_slice(func_get_args(), 2); 

        if (in_array('receptionist', $roles) && $request->url() == url('/queue/receptionist')) {
            return $next($request);
        }

        foreach ($roles as $role) 
        { 
            try 
            {   
                if (Auth::check() && Auth::user()->hasRole($role)) 
                {
                    return $next($request);
                }
                else
                {
                    return redirect('login')->with('exception',trans('app.you_are_not_authorized'));
                }
            } 
            catch (ModelNotFoundException $exception) 
            {
                dd('Could not find role ' . $role);
            }
        } 

        if (empty($roles))
            return redirect('login')->with('exception',trans('app.you_are_not_authorized'));
    }
}
