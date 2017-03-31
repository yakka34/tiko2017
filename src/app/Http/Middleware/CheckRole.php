<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole{
    public function handle($request, Closure $next, $role){

        if(!$request->user()->hasRole($role)){
            // Jos käyttäjällä ei ole haluttua roolia, siirry takaisin sinne mistä tultiin
            // Näytetään myös virhe
            return back()->with('error', 'Ei oikeutta!');
        }
        return $next($request);
    }
}