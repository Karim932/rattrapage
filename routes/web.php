<?php

use App\Http\Middleware\Authenticate;
use App\Models\Service;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckIfBanned;
use App\Http\Middleware\SetLocalization;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\PlanningController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdhesionsController;
use App\Http\Controllers\Admin\AnswerController;
use App\Http\Controllers\Admin\BenevoleServiceController;
use App\Http\Controllers\Admin\CandidatureController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\CollecteController;
use App\Http\Controllers\Commercant\CommercantCollecteController;
use App\Http\Controllers\Benevole\BenevoleCollecteController;
use App\Http\Controllers\CreneauController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PageNavController;


// mail vérifié ou non
use App\Http\Middleware\EnsureEmailIsVerified;
use App\Models\Inscription;

// Définit une route pour la localisation qui permet de changer la langue de l'application.
// Elle utilise un contrôleur invocable `LocalizationController` qui gère la mise à jour de la locale.
Route::get('/localization/{locale}', LocalizationController::class)->name('localization');

// Applique le middleware `SetLocalization` à un groupe de routes.
// Ce middleware est probablement conçu pour ajuster la locale de l'application basée sur la session ou d'autres logiques.
Route::middleware(SetLocalization::class)->group(function() {
    // Inclut les routes d'authentification à partir d'un autre fichier.
    require __DIR__.'/auth.php';

    Route::get('/accueil', [PageNavController::class, 'accueil'])->name('accueil'); 
    Route::get('/', [PageNavController::class, 'redirection']);
    
    
    Route::middleware([Authenticate::class, CheckIfBanned::class])->group(function () {
        // Paiement stripe dans (service Front)
        Route::prefix('stripe')->name('stripe.')->controller(PaymentController::class)->group(function (){
            Route::get('/create-checkout-session', 'createSession')->name('checkout');
            Route::get('/success', 'success')->name('success');
            Route::get('/cancel', 'cancel')->name('cancel');
        });

        // Groupe de routes pour la gestion du profil utilisateur avec des actions spécifiques gérées dans `ProfileController`.
        Route::name('profile.')->controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'edit')->name('edit'); 
            Route::patch('/profile', 'update')->name('update'); 
            Route::delete('/profile','destroy')->name('destroy'); 
        });
    
        // Avoir un rôle benevole ou commercant front
        Route::controller(AdhesionsController::class)->group(function(){
            Route::get('/benevoles', 'candidature')->name('benevoles');
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

        Route::prefix('creneaux')->name('adherent.')->controller(CreneauController::class)->group(function (){
            Route::get('/','index')->name('index');
            Route::get('filter', 'filter')->name('filter');
            Route::post('/creneaux/inscrire', 'inscrire')->name('inscrire');
        });
        Route::get('services', [InscriptionController::class, 'service'])->name('services');
        Route::get('/mon-historique', [InscriptionController::class, 'historique'])->name('adherent.historique');
        Route::delete('/plannings/{id}/cancel', [InscriptionController::class, 'cancel'])->name('plannings.cancel');
        
    });


    // POUR LE BACK OFFICE 
    // Groupe de routes pour les utilisateurs authentifiés, incluant un middleware pour vérifier si l'utilisateur est banni et s'il est admin.
    Route::middleware([Authenticate::class, AdminMiddleware::class, CheckIfBanned::class])->group(function () {

        // Route de ressource pour les utilisateurs, permettant de gérer les opérations CRUD.
        Route::resource('users', UserController::class);
        Route::resource('adhesion', CandidatureController::class);
        Route::resource('admin/services', ServiceController::class);
        Route::resource('skills', SkillController::class);

        // Route pour accéder au tableau de bord de l'administrateur
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->middleware('can:is-admin')->name('admin.dashboard');

        //Gestion des utilisateurs ADMIN
        Route::prefix('users')->name('users.')->controller(UserController::class)->group(function (){
            // Routes supplémentaires pour le filtrage des utilisateurs, le bannissement et le débannissement.
            Route::get('/filter-users', 'filterUsers')->name('filter'); // Filtre les utilisateurs.
            Route::post('ban/{id}', 'banUser')->name('ban');
            Route::post('unban/{id}', 'unbanUser')->name('unban');
        });
        Route::get('/mise-a-jour-user', [UserController::class, 'getUsersWithoutCandidature']);

        //Gestion des plannings ADMIN
        Route::prefix('plannings')->name('plannings.')->controller(PlanningController::class)->group(function (){
            // Route pour accéder à la vue du calendrier
            Route::get('/', 'index')->name('index'); 
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('{id}/', 'show')->name('show');
            Route::delete('{id}', 'destroy')->name('destroy'); 
            Route::get('{id}/edit', 'edit')->name('edit'); 
            Route::put('{id}/save', 'update')->name('update');
            Route::get('{id}/benevole', 'showBenevole')->name('benevole');
            Route::post('{id}/benevole', 'addBenevole')->name('addBenevole');
            Route::delete('{id}/benevole/{benevoleId}', 'removeBenevole')->name('removeBenevole');
            Route::get('{id}/inscrits', 'showInscrits')->name('inscrits');
            Route::delete('{planning}/inscriptions/{user}', 'destroyInscription')->name('inscriptions.destroy');
            Route::get('{id}/add-adherent', 'showAddAdherentForm')->name('addAdherent');
            Route::post('{id}/add-adherent', 'storeAdherent')->name('storeAdherent');
        });
        // Route API pour obtenir les événements du calendrier
        Route::get('/api/plannings', [PlanningController::class, 'getEvents'])->name('api.plannings');
        Route::post('/inscriptions/store', [InscriptionController::class, 'store'])->name('inscriptions.store');
        
        Route::prefix('admin/services')->name('services.')->controller(BenevoleServiceController::class)->group(function (){
            Route::get('add/benevole', 'create')->name('affecte');
            Route::post('benevole', 'store')->name('save');
            Route::get('{serviceId}/skills', 'skillShow');
        });

        Route::prefix('admin/adhesion/candidatures')->name('admin.adhesion.')->controller(CandidatureController::class)->group(function() {
            Route::get('{id}/{type}', 'show')->name('show');
            Route::post('{id}/accept', 'accept')->name('accept');
            Route::post('{id}/revoque', 'revoque')->name('revoque');
            Route::post('{id}', 'refuse')->name('refuse');
        });

        Route::prefix('answer')->name('answer.')->controller(AnswerController::class)->group(function() {
            Route::post('{id}', 'store')->name('store');
            Route::get('{id}', 'store')->name('store');
        });
        
    });    

});

// Partie de fares
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

