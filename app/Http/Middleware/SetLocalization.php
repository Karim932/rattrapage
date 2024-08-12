<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SetLocalization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Définit la locale de l'application en utilisant la valeur stockée dans la session sous la clé 'localization'.
        // Si aucune valeur n'est stockée dans la session pour cette clé, la configuration par défaut de la locale de l'application est utilisée.
        // `app()->setLocale()` est une méthode qui permet de configurer dynamiquement la locale de l'application durant l'exécution.
        // `session('localization', config('app.locale'))` tente de récupérer d'abord la locale à partir de la session.
        // Si aucune locale n'est définie dans la session, alors la valeur par défaut définie dans la configuration de l'application (`config('app.locale')`) est utilisée comme fallback.
        app()->setLocale(session('localization', config('app.locale')));

        // Passe la requête au middleware suivant dans la pile.
        // `$next($request)` invoque le prochain middleware avec la requête modifiée.
        // Ceci est typique dans les middleware Laravel où après avoir effectué les actions nécessaires,
        // la requête doit continuer son chemin à travers les autres middleware jusqu'à atteindre le contrôleur.
        return $next($request);

    }
}






