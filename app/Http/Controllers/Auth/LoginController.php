<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo() {
        if (auth()->user()->hasRole('admin')) {
            return '/admin';
        }
        elseif (auth()->user()->hasRole('receptionist')) {
            return '/receptionist';
        }
        elseif (auth()->user()->hasRole('user')) {
            return '/user';
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
