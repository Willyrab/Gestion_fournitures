<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticateDepartement
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $guard = 'departement')
    {
        // Vérification de l'authentification admin
        if (!Auth::guard($guard)->check()) {
            return redirect()->route('login.departement');
        }

        // Passer la requête au prochain middleware avec les en-têtes de cache désactivés
        $response = $next($request);

        // Ajout des en-têtes pour désactiver le cache
        return $response->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', '0');
    }
}
