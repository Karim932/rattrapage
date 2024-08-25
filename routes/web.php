<?php

use App\Http\Middleware\Authenticate;

use App\Models\Service;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
// mail vérifié ou non
use App\Http\Middleware\EnsureEmailIsVerified;

use App\Http\Middleware\CheckIfBanned;
use App\Http\Middleware\SetLocalization;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\PlanningController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdhesionsController;
use App\Http\Controllers\Admin\Answer;
use App\Http\Controllers\Admin\AnswerController;
use App\Http\Controllers\Admin\BenevoleServiceController;
use App\Http\Controllers\Admin\CandidatureController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\CollecteController;
use App\Http\Controllers\Commercant\CommercantCollecteController;
use App\Http\Controllers\Benevole\BenevoleCollecteController;
use App\Http\Controllers\PaymentController;

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

    // AdminMiddleware::class
    // Groupe de routes pour les utilisateurs authentifiés, incluant un middleware pour vérifier si l'utilisateur est banni.
    Route::middleware([Authenticate::class, CheckIfBanned::class])->group(function () {

        // Groupe de routes pour la gestion du profil utilisateur avec des actions spécifiques gérées dans `ProfileController`.
        Route::name('profile.')->controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'edit')->name('edit'); // Affiche le formulaire d'édition du profil.
            Route::patch('/profile', 'update')->name('update'); // Met à jour les informations du profil.
            Route::delete('/profile','destroy')->name('destroy'); // Supprime le profil de l'utilisateur.
        });

        // Route de ressource pour les utilisateurs, permettant de gérer les opérations CRUD via `CandidatureController`.
        Route::resource('users', UserController::class);
        Route::resource('adhesion', CandidatureController::class);
        Route::resource('admin/services', ServiceController::class);
        Route::resource('skills', SkillController::class);

       

        // Route pour accéder à la vue du calendrier
        Route::get('/plannings', [PlanningController::class, 'index'])->name('plannings.index');
        Route::get('/plannings/create', [PlanningController::class, 'create'])->name('plannings.create');
        Route::post('/plannings', [PlanningController::class, 'store'])->name('plannings.store');
        Route::get('/plannings/{id}/', [PlanningController::class, 'show'])->name('plannings.show');
        Route::delete('/plannings/{id}', [PlanningController::class, 'destroy'])->name('plannings.destroy');
        // Route pour éditer un planning
        Route::get('plannings/{id}/edit', [PlanningController::class, 'edit'])->name('plannings.edit');
        // Route pour mettre à jour un planning
        Route::put('plannings/{id}/save', [PlanningController::class, 'update'])->name('plannings.update');

        // Route API pour obtenir les événements du calendrier
        Route::get('/api/plannings', [PlanningController::class, 'getEvents'])->name('api.plannings');


        Route::get('admin/services/add/benevole', [BenevoleServiceController::class, 'create'])->name('services.affecte');
        Route::post('admin/services/benevole', [BenevoleServiceController::class, 'store'])->name('services.save');
        Route::get('/services/{serviceId}/skills', [BenevoleServiceController::class, 'skillShow']);


        Route::name('admin.adhesion.')->controller(CandidatureController::class)->group(function() {
            Route::get('/candidatures/{id}/{type}', 'show')->name('show');
            Route::post('/adhesion/{id}/accept', 'accept')->name('accept');
            Route::post('/adhesion/{id}/revoque', 'revoque')->name('revoque');
            Route::post('/adhesion/{id}', 'refuse')->name('refuse');
        });

        Route::get('/mise-a-jour-user', [UserController::class, 'getUsersWithoutCandidature']);

        Route::post('/answer/{id}', [AnswerController::class, 'store'])->name('answer.store');
        Route::get('/answer/{id}', [AnswerController::class, 'store'])->name('answer.store');



        // Routes supplémentaires pour le filtrage des utilisateurs, le bannissement et le débannissement.
        Route::get('/filter-users', [UserController::class, 'filterUsers'])->name('users.filter'); // Filtre les utilisateurs.
        Route::post('/users/ban/{id}', [UserController::class, 'banUser'])->name('users.ban');
        Route::post('/users/unban/{id}', [UserController::class, 'unbanUser'])->name('users.unban');

    });

    // Routes pour diverses fonctionnalités de l'application, chacune retournant une vue spécifique.
    Route::get('services', function () {
        $services = Service::all();
        return view('page_navbar/services', compact('services'));
    })->name('services');

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

    Route::get('/create-checkout-session', [PaymentController::class, 'createSession'])->name('checkout');
    Route::get('/success', function () {
        return 'Payment successful';
    })->name('success');
    Route::get('/cancel', function () {
        return 'Payment cancelled';
    })->name('cancel');


    // Avoir un rôle benevole ou commercant front
    Route::controller(AdhesionsController::class)->group(function(){
        Route::get('/adhesions/commercant', 'createCommercant')->name('commercant');
        Route::post('/adhesions/commercant', 'storeCommercant')->name('store.commercant');
        Route::post('/adhesions/commercant/{id}', 'updateCommercant')->name('update.commercant');
        Route::get('/adhesions/change/commercant', 'changeCommercant')->name('change.commercant');
        Route::get('/adhesions/benevole', 'createBenevole')->name('benevole');
        Route::get('/adhesions/change/benevole', 'changeBenevole')->name('change.benevole');
        Route::put('/adhesions/change/benevole/{id}', 'updateBenevole')->name('update.benevole');
        Route::post('/adhesions/benevole', 'storeBenevole')->name('store.benevole');
        Route::get('/adhesions/benevole/dashboard', 'dashboard')->name('dashboard.benevole');
    });

    // Route pour accéder au tableau de bord de l'administrateur, accessible uniquement aux utilisateurs ayant le rôle d'administrateur.
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('can:is-admin')->name('admin.dashboard');
});

//ADMIN GESTION COLLECTES
Route::middleware(['auth',])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('collectes', CollecteController::class);
    Route::post('collectes/{id}/assign', [CollecteController::class, 'assign'])->name('collectes.assign');
    Route::put('collectes/{id}/update-status', [CollecteController::class, 'updateStatus'])->name('collectes.updateStatus');
});

//INTERFACE COMMERCANT
Route::middleware(['auth'])->prefix('commercant')->group(function () {
    Route::get('/demande-collecte', [CommercantCollecteController::class, 'create'])->name('commercant.demande_collecte.create');
    Route::post('/demande-collecte', [CommercantCollecteController::class, 'store'])->name('commercant.demande_collecte.store');
    Route::get('/dashboard', [CommercantCollecteController::class, 'dashboard'])->name('commercant.dashboard');
    Route::put('/collecte/{id}/cancel', [CommercantCollecteController::class, 'cancel'])->name('commercant.collecte.cancel');
});

//INTERFACE BENEVOLE
Route::middleware(['auth'])->prefix('benevole')->name('benevole.')->group(function () {
    Route::get('/collectes', [BenevoleCollecteController::class, 'index'])->name('collectes.index');
    Route::get('/collectes/{id}', [BenevoleCollecteController::class, 'show'])->name('collectes.show');
    Route::put('/collectes/{id}/status', [BenevoleCollecteController::class, 'updateStatus'])->name('collectes.updateStatus');
    Route::get('/collectes/{id}/stock', [BenevoleCollecteController::class, 'stock'])->name('collectes.stock');
    Route::post('/{id}/check-products', [BenevoleCollecteController::class, 'checkProducts'])->name('collectes.checkProducts');
    Route::get('/{id}/add-products', [BenevoleCollecteController::class, 'addProducts'])->name('collectes.addProducts');
    Route::post('/{id}/store-new-products', [BenevoleCollecteController::class, 'storeNewProducts'])->name('collectes.storeNewProducts');
    Route::post('/{id}/store-stock', [BenevoleCollecteController::class, 'storeStock'])->name('collectes.storeStock');
});
