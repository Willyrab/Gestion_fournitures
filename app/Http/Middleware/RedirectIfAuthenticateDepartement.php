<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticateDepartement
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $guard = 'departement')
    {
        if (Auth::guard($guard)->check()) {
            return redirect('/BackOffice/Department/Index'); // Redirige si déjà authentifié
        }

        return $next($request);
    }
}
