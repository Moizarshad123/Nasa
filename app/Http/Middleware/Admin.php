<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
  
    public function handle(Request $request, Closure $next)
    {
        // if (Auth::check() == true && Auth::user()->role_id == 1) {
        if (Auth::check() == true) {
            return $next($request);
        } 
        // elseif(Auth::user()->role_id == 2) {
        //     return $next($request);
        // }
        return redirect()->to('login');
    }
}
