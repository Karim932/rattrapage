<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Cette route utilise le middleware 'auth:sanctum' pour s'assurer que seul un utilisateur authentifié peut y accéder.
// Elle récupère l'utilisateur actuellement connecté grâce à la méthode `$request->user()`.
// C'est une API typique pour récupérer le profil de l'utilisateur connecté dans des applications SPA ou mobiles utilisant Sanctum pour l'authentification.
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Cette route est destinée à être utilisée par une API pour récupérer les utilisateurs filtrés par un rôle spécifique.
// Elle utilise le UserController et la méthode filterByRole pour traiter la requête.
// La logique de filtrage par rôle doit être implémentée dans cette méthode du contrôleur.
Route::get('/users/role', [UserController::class, 'filterByRole'])->name('api.users.role');

// Cette route permet de récupérer tous les utilisateurs sans aucun filtre.
// Elle appelle la méthode getAllUsers du UserController.
// Cette méthode doit renvoyer tous les utilisateurs de la base de données, typiquement utilisée pour des vues d'administration ou des listes complètes.
Route::get('/users/all', [UserController::class, 'getAllUsers'])->name('api.users.all');


