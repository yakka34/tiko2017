<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole{
    public function handle($request, Closure $next, $role){

        if(!$request->user()->hasRole($role)){
            echo 'false';
            return view('home');
        }
        echo 'true';
        return $next($request);
    }
}