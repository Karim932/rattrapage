<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIfBanned
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
        // Vérifie si l'utilisateur est actuellement connecté et si son statut indique qu'il est banni.
        if (Auth::check() && Auth::user()->banned) {
            // Déconnecte l'utilisateur
            Auth::logout();

            // Redirige l'utilisateur vers la page de connexion avec un message d'erreur.
            return redirect('/login')->withErrors(['Your account has been banned.']);
        }

        // Si l'utilisateur n'est pas banni ou n'est pas connecté, le middleware passe la requête au prochain gestionnaire dans la chaîne.
        return $next($request);

    }
}

