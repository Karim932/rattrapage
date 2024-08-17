<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        // Appel de la méthode registerPolicies qui est souvent utilisée pour enregistrer les politiques d'autorisation
        // Cela associe les modèles aux politiques qui contrôlent les actions que les utilisateurs peuvent effectuer sur ces modèles.
        $this->registerPolicies();

        // Attente de la fin du processus de démarrage de l'application avant d'exécuter la fonction de rappel.
        // Cela garantit que toutes les dépendances de l'application sont chargées et disponibles.
        $this->app->booted(function () {
            // Définition d'une porte (Gate), qui est une façon dans Laravel de définir des règles d'autorisation.
            // 'is-admin' est le nom de la règle, et la fonction de rappel détermine si elle passe ou non.
            Gate::define('is-admin', function ($user) {
                // Appel de la méthode isAdmin sur l'objet utilisateur.
                // Cette méthode doit être définie dans la classe User et retourner un booléen.
                // Si elle retourne true, l'utilisateur est considéré comme un administrateur.
                return $user->isAdmin();
            });
        });
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Ce bloc est pour l'enregistrement des services
        // Ne pas placer de logique liée à Gate ou à Auth ici car tous les services ne sont pas encore chargés
    }
}

