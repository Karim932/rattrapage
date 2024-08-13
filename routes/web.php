<?php

use App\Http\Middleware\Authenticate;


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
// mail vérifié ou non
use App\Http\Middleware\EnsureEmailIsVerified;

use App\Http\Middleware\CheckIfBanned;
use App\Http\Middleware\SetLocalization;
use App\Http\Middleware\AdminMiddleware;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdhesionsController;
use App\Http\Controllers\Admin\Answer;
use App\Http\Controllers\Admin\CandidatureController;
use App\Http\Controllers\LocalizationController;

// Définit une route pour la localisation qui permet de changer la langue de l'application.
// Elle utilise un contrôleur invocable `LocalizationController` qui gère la mise à jour de la locale.
Route::get('/localization/{locale}', LocalizationController::class)->name('localization');

// Applique le middleware `SetLocalization` à un groupe de routes.
// Ce middleware est probablement conçu pour ajuster la locale de l'application basée sur la session ou d'autres logiques.
Route::middleware(SetLocalization::class)->group(function() {
    // Inclut les routes d'authentification à partir d'un autre fichier.
    require __DIR__.'/auth.php';

    // Route pour la page d'accueil de l'application.
    Route::get('/accueil', function () {
        return view('accueil');
    })->name('accueil');

    // Redirige la racine du site vers la route 'accueil'.
    Route::get('/', function () {
        return redirect()->route('accueil');
    });

    // Groupe de routes pour les utilisateurs authentifiés, incluant un middleware pour vérifier si l'utilisateur est banni.
    Route::middleware([Authenticate::class, AdminMiddleware::class, CheckIfBanned::class])->group(function () {

        // Groupe de routes pour la gestion du profil utilisateur avec des actions spécifiques gérées dans `ProfileController`.
        Route::name('profile.')->controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'edit')->name('edit'); // Affiche le formulaire d'édition du profil.
            Route::patch('/profile', 'update')->name('update'); // Met à jour les informations du profil.
            Route::delete('/profile','destroy')->name('destroy'); // Supprime le profil de l'utilisateur.
        });

        // Route de ressource pour les utilisateurs, permettant de gérer les opérations CRUD via `CandidatureController`.
        Route::resource('users', UserController::class);
        Route::resource('adhesion', CandidatureController::class);


        Route::name('admin.adhesion.')->controller(CandidatureController::class)->group(function() {
            Route::get('/candidatures/{id}/{type}', 'show')->name('show');
            Route::post('/adhesion/{id}/accept', 'accept')->name('accept');
            Route::post('/adhesion/{id}', 'refuse')->name('refuse');
            // Route::post('/adhesion/trie?={sort}&={direction}')->name('trie');
            // Route::get('/adhesions/filter', 'filtre')->name('filtre');

        });

        Route::post('/answer/{id}', [Answer::class, 'store'])->name('answer.store');


        // Routes supplémentaires pour le filtrage des utilisateurs, le bannissement et le débannissement.
        Route::get('/filter-users', [UserController::class, 'filterUsers'])->name('users.filter'); // Filtre les utilisateurs.
        Route::post('/users/ban/{id}', [UserController::class, 'banUser'])->name('users.ban'); // Bannit un utilisateur.
        Route::post('/users/unban/{id}', [UserController::class, 'unbanUser'])->name('users.unban'); // Débannit un utilisateur.

        // Routes pour diverses fonctionnalités de l'application, chacune retournant une vue spécifique.
        Route::get('services', function () {
            return view('page_navbar/services');
        })->name('services');

        // Route::get('adhesions', function () {
        //     return view('admin.adhesions.candidature');
        // })->name('adhesions');

        Route::get('/collectes', function () {
            return view('collectes');
        })->name('collectes');

        Route::get('/stocks', function () {
            return view('stocks');
        })->name('stocks');

        Route::get('/tournees', function () {
            return view('tournees');
        })->name('tournees');

        Route::get('/benevoles', function () {
            return view('benevoles');
        })->name('benevoles');

        Route::get('/contact', function () {
            return view('contact');
        })->name('contact');


        // Avoir un rôle benevole ou commercant front
        Route::get('/adhesions/commercant', [AdhesionsController::class, 'createCommercant'])->name('commercant');
        Route::post('/adhesions/commercant', [AdhesionsController::class, 'storeCommercant'])->name('store.commercant');
        Route::get('/adhesions/benevole', [AdhesionsController::class, 'createBenevole'])->name('benevole');
        Route::post('/adhesions/benevole',[AdhesionsController::class, 'storeBenevole'])->name('store.benevole');
    });

    // // Route pour afficher et soumettre le formulaire des commerçants
    // Route::get('/adhesion-commercants', function() {
    //     return view('page_navbar.commerçants.adhesion');
    // })->name('commercant');

    // // Route pour afficher et soumettre le formulaire des commerçants
    // Route::get('/adhesion-benevoles', function() {
    //     return view('page_navbar.benevoles.adhesion');
    // })->name('benevole');




    // Route pour accéder au tableau de bord de l'administrateur, accessible uniquement aux utilisateurs ayant le rôle d'administrateur.
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('can:is-admin')->name('admin.dashboard');
});
