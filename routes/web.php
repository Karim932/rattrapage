<?php

use App\Http\Middleware\Authenticate;
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
use App\Http\Controllers\Benevole\BenevoleDistributionController;
use App\Http\Controllers\CreneauController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PageNavController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\ProduitController;
use App\Http\Controllers\Admin\AnnonceController;
use App\Http\Controllers\Admin\DistributionController;
use App\Http\Controllers\AnnonceAdherentController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\ServiceBenevoleProposeController;
use App\Http\Controllers\ContactController;


// mail vérifié ou non
use App\Http\Middleware\EnsureEmailIsVerified;


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

        Route::get('/annonces/adherent', [AnnonceAdherentController::class, 'index'] )->name('annonces.adherent.index');
        Route::get('annonces/create/adherent', [AnnonceAdherentController::class, 'create'])->name('annonces.adherent.create');
        Route::post('annonces/save', [AnnonceAdherentController::class, 'store'])->name('annonces.adherent.store');
        Route::get('annonces/voir/{id}', [AnnonceAdherentController::class, 'show'])->name('annonces.adherent.show');
        Route::get('/annonces/{annonce}/edit/adherent', [AnnonceAdherentController::class, 'edit'])->name('annonces.adherent.edit');
        Route::put('/annonces/{annonce}/adherent', [AnnonceAdherentController::class, 'update'])->name('annonces.adherent.update');
        Route::delete('/annonces/{annonce}/delete', [AnnonceAdherentController::class, 'destroy'])->name('annonces.adherent.destroy');

    
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

        Route::get('/contact', [ContactController::class, 'showForm'])->name('contact');
        Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

        Route::prefix('creneaux')->name('adherent.')->controller(CreneauController::class)->group(function (){
            Route::get('/','index')->name('index');
            Route::get('filter', 'filter')->name('filter');
            Route::post('/creneaux/inscrire', 'inscrire')->name('inscrire');
        });
        Route::get('services', [InscriptionController::class, 'service'])->name('services');
        Route::get('/mon-historique', [InscriptionController::class, 'historique'])->name('adherent.historique');
        Route::delete('/plannings/{id}/cancel', [InscriptionController::class, 'cancel'])->name('plannings.cancel');


        Route::prefix('benevole')->name('propose.benevole.')->group(function () {
            Route::get('/create/test', [ServiceBenevoleProposeController::class, 'createBenevole'])->name('create');
            Route::post('/store/propose/test', [ServiceBenevoleProposeController::class, 'storeBenevole'])->name('store');
        });

    });


    // POUR LE BACK OFFICE 
    // Groupe de routes pour les utilisateurs authentifiés, incluant un middleware pour vérifier si l'utilisateur est banni et s'il est admin.
    Route::middleware([Authenticate::class, AdminMiddleware::class, CheckIfBanned::class])->group(function () {

        // Route de ressource pour les utilisateurs, permettant de gérer les opérations CRUD.
        Route::resource('users', UserController::class);
        Route::resource('adhesion', CandidatureController::class);
        Route::resource('admin/services', ServiceController::class);
        Route::resource('skills', SkillController::class);
        Route::resource('annonces', AnnonceController::class);
        Route::resource('propose', ServiceBenevoleProposeController::class);

        Route::get('admin/contact', [ContactController::class, 'index'])->name('admin.contact.index');
        Route::get('admin/contact/{id}', [ContactController::class, 'show'])->name('admin.contact.show');
        Route::get('admin/contact/{id}/edit', [ContactController::class, 'edit'])->name('admin.contact.edit');
        Route::put('admin/contact/{id}', [ContactController::class, 'update'])->name('admin.contact.update');
        Route::delete('admin/contact/{id}', [ContactController::class, 'destroy'])->name('admin.contact.destroy');

        // Route pour accéder au tableau de bord de l'administrateur
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->middleware('can:is-admin')->name('admin.dashboard');

        //Gestion des utilisateurs ADMIN
        Route::prefix('users')->name('users.')->controller(UserController::class)->group(function (){
            Route::post('ban/{id}', 'banUser')->name('ban');
            Route::post('unban/{id}', 'unbanUser')->name('unban');
        });
        Route::get('/mise-a-jour-user', [UserController::class, 'getUsersWithoutCandidature']);
        Route::get('/filter-users', [UserController::class, 'filterUsers'])->name('filter'); 


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

        Route::put('/accept/propose/{id}', [ServiceBenevoleProposeController::class, 'updatePropose'])->name('update.propose');

        
        Route::prefix('admin/services')->name('services.')->controller(BenevoleServiceController::class)->group(function (){
            Route::get('add/benevole', 'create')->name('affecte');
            Route::post('benevole', 'store')->name('save');
            Route::get('/{service}/skills', 'skillShow');
            Route::post('/benevole/remove/{adhesion_id}', 'removeBenevole')->name('benevole.detach');

        });

        Route::prefix('admin/adhesion/candidatures')->name('admin.adhesion.')->controller(CandidatureController::class)->group(function() {
            Route::get('{id}/{type}', 'show')->name('show');
            Route::post('{id}/accept', 'accept')->name('accept');
            Route::post('{id}/revoque', 'revoque')->name('revoque');
            Route::post('{id}', 'refuse')->name('refuse');
        });

        Route::prefix('answer')->name('answer.')->controller(AnswerController::class)->group(function() {
            Route::post('{id}/response', 'store')->name('response.store');
            Route::get('{id}/', 'store')->name('store');
        });
        
    });    

});

// Partie de fares
//ADMIN GESTION COLLECTES
Route::middleware([Authenticate::class, AdminMiddleware::class, CheckIfBanned::class])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('collectes', CollecteController::class);
    Route::post('collectes/{id}/assign', [CollecteController::class, 'assign'])->name('collectes.assign');
    Route::put('collectes/{id}/update-status', [CollecteController::class, 'updateStatus'])->name('collectes.updateStatus');
});

Route::middleware([Authenticate::class, AdminMiddleware::class, CheckIfBanned::class])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('stock', StockController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('produits', ProduitController::class)->except(['show']);
});

Route::middleware([Authenticate::class, AdminMiddleware::class, CheckIfBanned::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('distributions', [DistributionController::class, 'index'])->name('distributions.index');
    Route::get('distributions/create', [DistributionController::class, 'create'])->name('distributions.create');
    Route::get('distributions/{id}/edit', [DistributionController::class, 'edit'])->name('distributions.edit');
    Route::put('distributions/{id}/update', [DistributionController::class, 'update'])->name('distributions.update');
    Route::delete('distributions/{distribution}/destroy', [DistributionController::class, 'destroy'])->name('distributions.destroy');
    Route::post('distributions/store-step1', [DistributionController::class, 'storeStep1'])->name('distributions.storeStep1');
    Route::get('distributions/selectStock', [DistributionController::class, 'selectStock'])->name('distributions.selectStock');
    Route::post('distributions/store-step2', [DistributionController::class, 'storeStep2'])->name('distributions.storeStep2');


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

    Route::get('/distributions/{id}', [BenevoleDistributionController::class, 'show'])->name('distributions.show');
    Route::put('distributions/{id}/status', [BenevoleDistributionController::class, 'updateStatus'])->name('distributions.updateStatus');
    Route::put('distributions/{id}/confirm', [BenevoleDistributionController::class, 'confirmerDistribution'])->name('distributions.confirm');
});




use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;


// Simuler une erreur 405 MethodNotAllowedHttpException
Route::get('/test-405', function () {
    throw new MethodNotAllowedHttpException([]);
});

// Simuler une erreur 403 AuthorizationException
Route::get('/test-403', function () {
    abort(403);
});

Route::get('/test-500', function () {
    abort(500); // Simuler une erreur 500 Internal Server Error
});

Route::get('/test-419', function () {
    abort(419); // Simuler une erreur 419 CSRF token mismatch
});