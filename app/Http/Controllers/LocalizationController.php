<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocalizationController extends Controller
{
    /**
     * Handle the incoming request.
     */

    // elle permet à un objet de se comporter comme une fonction.
    // Lorsque vous essayez d'appeler un objet comme s'il s'agissait d'une fonction,
    // PHP recherche automatiquement cette méthode `__invoke()` dans la classe de cet objet et l'exécute
    public function __invoke($locale)
    {
        // Vérifie si la valeur de $locale est dans la liste des locales autorisées définies dans la configuration.
        if (!in_array($locale, config('localization.locales'))) {
            // Si $locale n'est pas dans la liste autorisée, termine la requête et renvoie une erreur HTTP 400 (Bad Request).
            abort(400);
        }

        // Stocke la valeur de la locale dans la session sous la clé 'localization'.
        session(['localization' => $locale]);
        // Redirige l'utilisateur à la page d'où il vient.
        return redirect()->back();
    }
}
